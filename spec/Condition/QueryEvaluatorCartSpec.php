<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace spec\Riesenia\Utility\Condition;

use Litipk\BigNumbers\Decimal;
use PhpSpec\ObjectBehavior;

class QueryEvaluatorCartSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'total' => [
                'field' => 'totals',
                'type' => 'decimal',
                'operators' => ['>', '<', '>=', '<=', '=']
            ]
        ]);
    }

    public function it_compares_delimals()
    {
        $condition = $this->parse('total > 20')->getWrappedObject();
        expect($condition(['totals' => [Decimal::fromFloat(20.54, 2)]]))->toBe(true);
        expect($condition(['totals' => [Decimal::fromInteger(20)]]))->toBe(false);

        $condition = $this->parse('total >= 20')->getWrappedObject();
        expect($condition(['totals' => [Decimal::fromFloat(20.54, 2)]]))->toBe(true);
        expect($condition(['totals' => [Decimal::fromInteger(20)]]))->toBe(true);

        $condition = $this->parse('total = 20.54')->getWrappedObject();
        expect($condition(['totals' => [Decimal::fromFloat(20.54, 2)]]))->toBe(true);
        expect($condition(['totals' => [Decimal::fromFloat(20.53)]]))->toBe(false);
        expect($condition(['totals' => [Decimal::fromFloat(20.541)]]))->toBe(false);
        expect($condition(['totals' => [Decimal::fromFloat(21.54)]]))->toBe(false);
    }
}
