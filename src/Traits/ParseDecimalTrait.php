<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Traits;

/**
 * Add _parseDecimal method.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
trait ParseDecimalTrait
{
    /**
     * Decimal parser.
     *
     * @param mixed $number
     * @param array $options
     * @param bool  $allowNull
     *
     * @return float|null
     */
    protected function _parseDecimal($number, array $options = [], bool $allowNull = false): ?float
    {
        if ($allowNull && $number === null) {
            return null;
        }

        if (isset($options['thousands_separator'])) {
            $number = \str_replace($options['thousands_separator'], '', $number);
        }

        $number = \str_replace(',', '.', $number);

        return (float) \strval(\preg_replace('/[^0-9.-]/', '', $number));
    }
}
