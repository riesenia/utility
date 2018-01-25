<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Condition;

use Litipk\BigNumbers\Decimal;
use Riesenia\Utility\Traits\ParseDecimalTrait;

/**
 * Class parsing written condition (query) to a function that evaluates aggregates condition for riesenia/cart package.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class QueryEvaluatorCart extends QueryEvaluatorCallable
{
    use ParseDecimalTrait;

    /**
     * Parse condition divided to field, operator and value.
     *
     * @param string $field
     * @param string $operator
     * @param string $value
     *
     * @return callable
     */
    protected function _parseCondition(string $field, string $operator, string $value)
    {
        // sum function
        $sum = function ($item) use ($field) {
            $sum = Decimal::fromInteger(0);

            foreach ($item[$field] as $s) {
                $sum = $sum->add($s);
            }

            return $sum;
        };

        // convert value to decimal
        $value = Decimal::fromFloat((float) $this->_parseDecimal($value));

        switch ($operator) {
            case '=':
                return function ($item) use ($sum, $value) {
                    $total = $sum($item);

                    return $total->equals($value);
                };

            case 'NOT':
                return function ($item) use ($sum, $value) {
                    $total = $sum($item);

                    return $total->equals($value);
                };

            case '>=':
                return function ($item) use ($sum, $value) {
                    $total = $sum($item);

                    return trim($total) >= trim($value);
                };

            case '<=':
                return function ($item) use ($sum, $value) {
                    $total = $sum($item);

                    return trim($total) <= trim($value);
                };

            case '>':
                return function ($item) use ($sum, $value) {
                    $total = $sum($item);

                    return trim($total) > trim($value);
                };

            case '<':
                return function ($item) use ($sum, $value) {
                    $total = $sum($item);

                    return trim($total) < trim($value);
                };
        }

        throw new QueryEvaluatorException(['placeholder' => $field, 'operator' => $operator], QueryEvaluatorException::UNKNOWN_OPERATOR);
    }
}
