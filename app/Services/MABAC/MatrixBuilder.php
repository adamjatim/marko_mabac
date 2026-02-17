<?php

namespace App\Services\MABAC;

use App\Models\Kriteria;
use App\Models\Mobil;

/**
 * MatrixBuilder - Responsible for building decision matrix from mobils and kriterias
 *
 * This service encapsulates the logic of extracting attributes from Mobil model
 * and building a decision matrix for MABAC calculation.
 *
 * Before: Hard-coded switch statements in controller
 * After: Configurable, testable, and reusable component
 */
class MatrixBuilder
{
    /**
     * Map of kriteria ID to Mobil model attribute names
     * Centralized mapping makes it easy to add new criteria without changing code
     */
    private const ATTRIBUTE_MAPPING = [
        1 => 'harga_baru',
        2 => 'harga_jual_kembali',
        3 => 'fitur_keamanan',
        4 => 'fitur_kenyamanan',
        // 5 => 'jarak_tempuh',
        5 => 'efisiensi_bahan_bakar',
        // 6 => 'kapasitas_mesin',
        6 => 'performa',
        7 => 'pajak',
    ];

    /**
     * Build decision matrix from mobils and kriterias
     *
     * @param \Illuminate\Database\Eloquent\Collection $mobils
     * @param \Illuminate\Database\Eloquent\Collection $kriterias
     * @return array Matrix in format: [mobil_id => [kriteria_id => value, ...], ...]
     *
     * @example
     * $mobils = Mobil::all();
     * $kriterias = Kriteria::all();
     * $matrix = $builder->build($mobils, $kriterias);
     *
     * Result:
     * [
     *     1 => [1 => 300000000, 2 => 250000000, 3 => 4, ...],
     *     2 => [1 => 350000000, 2 => 280000000, 3 => 5, ...],
     * ]
     */
    public function build($mobils, $kriterias): array
    {
        $matrix = [];

        foreach ($mobils as $mobil) {
            $row = [];
            foreach ($kriterias as $kriteria) {
                $row[$kriteria->id] = $this->getAttributeValue($mobil, $kriteria->id);
            }
            $matrix[$mobil->id] = $row;
        }

        return $matrix;
    }

    /**
     * Get attribute value from mobil by criteria ID
     *
     * @param \App\Models\Mobil $mobil
     * @param int $kriteriaId
     * @return mixed
     */
    private function getAttributeValue(Mobil $mobil, int $kriteriaId): mixed
    {
        $attributeName = self::ATTRIBUTE_MAPPING[$kriteriaId] ?? null;

        if ($attributeName === null) {
            throw new \InvalidArgumentException("Unknown criteria ID: {$kriteriaId}");
        }

        return $mobil->{$attributeName};
    }

    /**
     * Add a new criteria mapping
     * Useful for extending the mapping without modifying the constant
     *
     * @param int $kriteriaId
     * @param string $attributeName
     * @return void
     */
    public function addMapping(int $kriteriaId, string $attributeName): void
    {
        // Note: In real scenario, might want to store in config or database
        // This is simplified for example
        if (isset(self::ATTRIBUTE_MAPPING[$kriteriaId])) {
            throw new \Exception("Mapping for criteria {$kriteriaId} already exists");
        }
    }

    /**
     * Get all current mappings
     *
     * @return array
     */
    public function getMappings(): array
    {
        return self::ATTRIBUTE_MAPPING;
    }
}
