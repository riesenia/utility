<?php
namespace spec\Riesenia\Utility\Condition;

use PhpSpec\ObjectBehavior;
use Riesenia\Utility\Condition\QueryEvaluatorException;

class QueryEvaluatorCallableSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'pid' => [
                'field' => 'id',
                'operators' => ['=', 'NOT', 'IN', 'NOT IN']
            ],
            'name' => [
                'field' => 'name',
                'operators' => ['=', 'NOT', 'CONTAINS']
            ],
            'price' => [
                'field' => 'unit_price',
                'operators' => ['>=', '>', '<', '<=']
            ]
        ]);
    }

    public function it_parses_simple_conditions()
    {
        $condition = $this->parse('pid = 56')->getWrappedObject();
        expect($condition(['id' => 56]))->toBe(true);
        expect($condition(['id' => 55]))->toBe(false);
        expect($condition(['pid' => 56]))->toBe(false);

        $condition = $this->parse('name CONTAINS abcd etc')->getWrappedObject();
        expect($condition(['name' => 'wsabcd etc']))->toBe(true);
        expect($condition(['name' => 'abcd etc']))->toBe(true);
        expect($condition(['name' => 'abcd . etc']))->toBe(false);
        expect($condition([]))->toBe(false);

        $condition = $this->parse('price >= 10')->getWrappedObject();
        expect($condition(['unit_price' => 10]))->toBe(true);
        expect($condition(['unit_price' => 10.0]))->toBe(true);
        expect($condition(['unit_price' => 10.01]))->toBe(true);
        expect($condition(['unit_price' => 9.99]))->toBe(false);
    }

    public function it_parses_and_condition()
    {
        $condition = $this->parse('pid IN 2, 3 AND price >= 10')->getWrappedObject();
        expect($condition(['id' => 2, 'unit_price' => 10]))->toBe(true);
        expect($condition(['id' => 4, 'unit_price' => 12]))->toBe(false);
        expect($condition(['id' => 2, 'unit_price' => 9]))->toBe(false);
        expect($condition(['id' => 2]))->toBe(false);
    }

    public function it_parses_or_condition()
    {
        $condition = $this->parse('pid IN 2, 3 OR price >= 10')->getWrappedObject();
        expect($condition(['unit_price' => 10]))->toBe(true);
        expect($condition(['id' => 2]))->toBe(true);
        expect($condition(['id' => 5, 'unit_price' => 9.99]))->toBe(false);
    }

    public function it_parses_and_or_condition_with_correct_precedence()
    {
        $condition = $this->parse('pid IN 2, 3 AND price >= 10 OR name = x')->getWrappedObject();
        expect($condition(['name' => 'x']))->toBe(true);
        expect($condition(['id' => 2, 'unit_price' => 12.4]))->toBe(true);
        expect($condition(['name' => 'xy']))->toBe(false);
        expect($condition(['id' => 2]))->toBe(false);
    }
}
