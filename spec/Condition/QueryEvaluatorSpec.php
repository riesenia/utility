<?php
namespace spec\Riesenia\Utility\Condition;

use PhpSpec\ObjectBehavior;
use Riesenia\Utility\Condition\QueryEvaluatorException;

class QueryEvaluatorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'pid' => [
                'field' => 'id',
                'operators' => ['=', 'NOT', 'IN', 'NOTIN']
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
        $this->parse('pid = 56')->shouldReturn(['id' => '56']);
        $this->parse('pid NOT 3')->shouldReturn(['OR' => ['id !=' => '3', 'id IS NULL']]);
        $this->parse('pid IN 2, 3')->shouldReturn(['id IN' => ['2', '3']]);
        $this->parse('name CONTAINS abcd etc')->shouldReturn(['name LIKE' => '%abcd etc%']);
    }

    public function it_parses_and_condition()
    {
        $this->parse('pid IN 2, 3 AND price >= 10')->shouldReturn(['AND' => [
            ['id IN' => ['2', '3']],
            ['unit_price >=' => '10']
        ]]);
    }

    public function it_parses_or_condition()
    {
        $this->parse('pid IN 2, 3 OR price >= 10')->shouldReturn(['OR' => [
            ['id IN' => ['2', '3']],
            ['unit_price >=' => '10']
        ]]);
    }

    public function it_parses_and_or_condition_with_correct_precedence()
    {
        $this->parse('pid IN 2, 3 AND price >= 10 OR name = x')->shouldReturn(['OR' => [
            ['AND' => [
                ['id IN' => ['2', '3']],
                ['unit_price >=' => '10']
            ]],
            ['name' => 'x']
        ]]);
    }

    public function it_parses_complex_condition_with_parenthesis()
    {
        $this->parse('pid IN 2, 3 AND ((price >= 10 OR name = x) OR name CONTAINS y)')->shouldReturn(['AND' => [
            ['id IN' => ['2', '3']],
            ['OR' => [
                ['OR' => [
                    ['unit_price >=' => '10'],
                    ['name' => 'x']
                ]],
                ['name LIKE' => '%y%']
            ]]
        ]]);
    }

    public function it_adds_prefix_correctly()
    {
        $this->beConstructedWith([
            '_prefix' => 'Product',
            'pid' => [
                'field' => 'id',
                'operators' => ['=', 'NOT', 'IN', 'NOTIN']
            ],
            'price' => [
                'field' => 'Price.unit_price',
                'operators' => ['>=', '>', '<', '<=']
            ]
        ]);

        $this->parse('pid IN 2, 3 OR price >= 10')->shouldReturn(['OR' => [
            ['Product.id IN' => ['2', '3']],
            ['Price.unit_price >=' => '10']
        ]]);
    }

    public function it_throws_exception_for_incorrect_query()
    {
        $this->shouldThrow(new QueryEvaluatorException(['placeholder' => 'pid'], QueryEvaluatorException::INVALID_CONDITION))->duringParse('pid =');
        $this->shouldThrow(new QueryEvaluatorException(['position' => 8], QueryEvaluatorException::MISSING_OPENING_PARENTHESIS))->duringParse('pid = 56)');
        $this->shouldThrow(new QueryEvaluatorException(['position' => 0], QueryEvaluatorException::MISSING_CLOSING_PARENTHESIS))->duringParse('(pid = 56');
        $this->shouldThrow(new QueryEvaluatorException(['placeholder' => 'id'], QueryEvaluatorException::UNKNOWN_PLACEHOLDER))->duringParse('id NOT 3');
        $this->shouldThrow(new QueryEvaluatorException(['placeholder' => 'pid', 'operator' => '>'], QueryEvaluatorException::UNKNOWN_OPERATOR))->duringParse('pid > 3');
    }
}
