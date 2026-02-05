<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Mobil;
use App\Services\MABAC\MABACCalculator;
use App\Services\MABAC\MABACException;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PerhitunganController - REFACTORED VERSION
 * 
 * BEFORE: 217 lines with all business logic in controller
 * AFTER: ~100 lines, delegating to services
 * 
 * Responsibilities:
 * - Handle HTTP requests/responses
 * - Validate input
 * - Coordinate with services
 * - Return views
 * 
 * Business logic is moved to MABAC services
 */
class PerhitunganControllerRefactored extends Controller
{
    /**
     * Inject MABAC calculator via dependency injection
     */
    public function __construct(
        private MABACCalculator $calculator
    ) {}

    /**
     * Show calculation form
     * 
     * @return View
     */
    public function index(): View
    {
        $mobils = Mobil::all();
        $kriterias = Kriteria::all();

        return view('perhitungan.index', [
            'mobils' => $mobils,
            'kriterias' => $kriterias,
        ]);
    }

    /**
     * Calculate MABAC and return results
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function calculate(Request $request)
    {
        // Validate input
        $validated = $this->validateInput($request);

        // Get data
        $kriterias = Kriteria::all();
        $mobils = Mobil::whereIn('id', $validated['mobil_ids'])->get();
        $weights = $this->extractAndNormalizeWeights($request, $kriterias);

        try {
            // Perform calculation using injected service
            $results = $this->calculator->calculate($mobils, $kriterias, $weights);

            return view('perhitungan.hasil', [
                'results' => $results,
                'mobils' => $mobils,
                'kriterias' => $kriterias,
            ]);
        } catch (MABACException $e) {
            return redirect()
                ->route('perhitungan.index')
                ->with('error', 'Perhitungan gagal: ' . $e->getMessage());
        }
    }

    /**
     * Get detailed calculation report (for debugging/analysis)
     * 
     * Useful for understanding calculation steps and troubleshooting
     * 
     * @param Request $request
     * @return mixed
     */
    public function getDetailedReport(Request $request)
    {
        $validated = $this->validateInput($request);

        $kriterias = Kriteria::all();
        $mobils = Mobil::whereIn('id', $validated['mobil_ids'])->get();
        $weights = $this->extractAndNormalizeWeights($request, $kriterias);

        try {
            $report = $this->calculator->getDetailedReport($mobils, $kriterias, $weights);

            // Return JSON for debugging
            return response()->json($report);
        } catch (MABACException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Validate input from request
     * 
     * @param Request $request
     * @return array Validated data
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateInput(Request $request): array
    {
        return $request->validate([
            'mobil_ids' => [
                'required',
                'array',
                'min:2', // Minimum 2 mobils required
            ],
            'mobil_ids.*' => [
                'required',
                'integer',
                'exists:mobils,id',
            ],
        ], [
            'mobil_ids.required' => 'Silakan pilih mobil yang ingin dibandingkan',
            'mobil_ids.min' => 'Minimal pilih 2 mobil untuk melakukan perhitungan MABAC',
            'mobil_ids.*.exists' => 'Salah satu mobil tidak ditemukan',
        ]);
    }

    /**
     * Extract weights from request and normalize them to sum = 1
     * 
     * Uses default weights if not provided in request
     * 
     * @param Request $request
     * @param \Illuminate\Database\Eloquent\Collection $kriterias
     * @return array Normalized weights [kriteria_id => weight]
     */
    private function extractAndNormalizeWeights(Request $request, $kriterias): array
    {
        $weights = [];

        // Extract weights from request or use defaults
        foreach ($kriterias as $kriteria) {
            $key = 'bobot_' . $kriteria->id;
            $weights[$kriteria->id] = (float) ($request->input($key) ?? $kriteria->bobot_default);
        }

        // Normalize weights to sum = 1
        $sum = array_sum($weights);
        if ($sum > 0) {
            $weights = array_map(fn($w) => $w / $sum, $weights);
        }

        return $weights;
    }
}
