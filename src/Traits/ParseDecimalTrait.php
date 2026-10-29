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
     * @param mixed               $number
     * @param array<string,mixed> $options
     * @param bool                $allowNull
     *
     * @return float|null
     */
    protected function _parseDecimal($number, array $options = [], bool $allowNull = false): ?float
    {
        if ($number === null) {
            return $allowNull ? null : 0.0;
        }

        // PHP 8 does not convert bools and objects to string in str_replace() under strict types
        $number = \is_scalar($number) || (\is_object($number) && \method_exists($number, '__toString')) ? \strval($number) : $number;

        if (isset($options['thousands_separator'])) {
            $number = \str_replace($options['thousands_separator'], '', $number);
        }

        $number = \str_replace(',', '.', $number);

        return (float) \strval(\preg_replace('/[^0-9.-]/', '', $number));
    }
}
