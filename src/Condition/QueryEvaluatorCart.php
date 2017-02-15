<?php
namespace Riesenia\Utility\Condition;

use Litipk\BigNumbers\Decimal;
use Riesenia\Utility\Traits\ParseDecimalTrait;

/**
 * Class parsing written condition (query) to a function returning bool.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class QueryEvaluatorCart extends QueryEvaluatorCallable
{
    use ParseDecimalTrait;

    /**
     * Parse condition divided to field, operator and value
     *
     * @param string field
     * @return array
     */
    protected function _parseCondition($field, $operator, $value)
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
        $value = Decimal::fromFloat($this->_parseDecimal($value));

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

        return [$field . $operator => $value];
    }
}
