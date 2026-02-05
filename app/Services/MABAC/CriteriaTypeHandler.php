<?php

namespace App\Services\MABAC;

/**
 * CriteriaTypeHandler - Handles benefit/cost type logic
 * 
 * This service encapsulates the business logic for handling different criteria types.
 * Before: if-else statements repeated in multiple methods
 * After: Single source of truth for criteria type handling
 */
class CriteriaTypeHandler
{
    public const TYPE_BENEFIT = 'benefit';
    public const TYPE_COST = 'cost';

    /**
     * Check if criteria is of benefit type
     * 
     * For benefit criteria: Higher value is better
     * For cost criteria: Lower value is better
     * 
     * @param string $type
     * @return bool
     */
    public function isBenefit(string $type): bool
    {
        return $type === self::TYPE_BENEFIT;
    }

    /**
     * Check if criteria is of cost type
     * 
     * @param string $type
     * @return bool
     */
    public function isCost(string $type): bool
    {
        return $type === self::TYPE_COST;
    }

    /**
     * Calculate Border Approximation Area (BAA) for a criteria
     * 
     * For benefit: BAA = minimum value (we want to exceed minimum)
     * For cost: BAA = maximum value (we want to stay below maximum)
     * 
     * @param array $values Array of weighted values for a criteria across all mobils
     * @param string $type Criteria type ('benefit' or 'cost')
     * @return float|int The BAA value
     * 
     * @example
     * // Benefit: Fitur Keamanan (higher is better)
     * $baa = $handler->calculateBAA([4.5, 3.2, 5.0], 'benefit');
     * // Returns: 3.2 (minimum)
     * 
     * // Cost: Harga (lower is better)
     * $baa = $handler->calculateBAA([300M, 250M, 350M], 'cost');
     * // Returns: 350M (maximum)
     */
    public function calculateBAA(array $values, string $type): float|int
    {
        if (empty($values)) {
            return 0;
        }

        if ($this->isBenefit($type)) {
            return min($values);
        } else {
            return max($values);
        }
    }

    /**
     * Calculate Q matrix value (distance from BAA) for single element
     * 
     * For benefit: Qi+ = normalized_value - BAA
     *              Higher normalized value relative to BAA = higher score
     * 
     * For cost: Qi- = BAA - normalized_value
     *           Lower normalized value relative to BAA = higher score
     * 
     * @param float $normalizedValue The normalized/weighted value
     * @param float $baa Border Approximation Area
     * @param string $type Criteria type
     * @return float The Q value
     * 
     * @example
     * // Benefit: normalized=4.5, BAA=3.2
     * $q = $handler->calculateQ(4.5, 3.2, 'benefit');
     * // Returns: 1.3 (positive, exceeds BAA)
     * 
     * // Cost: normalized=250M, BAA=350M
     * $q = $handler->calculateQ(250, 350, 'cost');
     * // Returns: 100 (positive, below BAA)
     */
    public function calculateQ(float $normalizedValue, float $baa, string $type): float
    {
        if ($this->isBenefit($type)) {
            return $normalizedValue - $baa;
        } else {
            return $baa - $normalizedValue;
        }
    }

    /**
     * Get description for criteria type (for UI/reporting)
     * 
     * @param string $type
     * @return string
     */
    public function getTypeDescription(string $type): string
    {
        return $this->isBenefit($type)
            ? 'Semakin Tinggi Semakin Baik'
            : 'Semakin Rendah Semakin Baik';
    }

    /**
     * Validate criteria type
     * 
     * @param string $type
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function validate(string $type): bool
    {
        if (!in_array($type, [self::TYPE_BENEFIT, self::TYPE_COST])) {
            throw new \InvalidArgumentException("Invalid criteria type: {$type}. Must be 'benefit' or 'cost'");
        }
        return true;
    }
}
