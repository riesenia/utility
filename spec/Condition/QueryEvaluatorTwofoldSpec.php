<?php
namespace spec\Riesenia\Utility\Condition;

use PhpSpec\ObjectBehavior;
use Riesenia\Utility\Condition\QueryEvaluatorException;

class QueryEvaluatorTwofoldSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'P1' => [
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
                    'operators' => ['>=', '>', '<', '<='] ]
                ],
            'P2' => [
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
            ]
        ]);
    }

    public function it_parses_simple_conditions()
    {
        $this->parse('P1.pid = 56')->shouldReturn(['P1.id' => '56']);
        $this->parse('P1.pid NOT 3')->shouldReturn(['P1.id !=' => '3']);
        $this->parse('P1.pid IN 2, 3')->shouldReturn(['P1.id IN' => ['2', '3']]);
        $this->parse('P2.name CONTAINS abcd etc')->shouldReturn(['P2.name LIKE' => '%abcd etc%']);
    }

    public function it_parses_and_condition()
    {
        $this->parse('P1.pid IN 2, 3 AND P2.price >= 10')->shouldReturn(['AND' => [
            ['P1.id IN' => ['2', '3']],
            ['P2.unit_price >=' => '10']
        ]]);
    }

    public function it_parses_or_condition()
    {
        $this->parse('P2.pid IN 2, 3 OR P1.price >= 10')->shouldReturn(['OR' => [
            ['P2.id IN' => ['2', '3']],
            ['P1.unit_price >=' => '10']
        ]]);
    }

    public function it_parses_and_or_condition_with_correct_precedence()
    {
        $this->parse('P1.pid IN 2, 3 AND P1.price >= 10 OR P1.name = x')->shouldReturn(['OR' => [
            ['AND' => [
                ['P1.id IN' => ['2', '3']],
                ['P1.unit_price >=' => '10']
            ]],
            ['P1.name' => 'x']
        ]]);
    }

    public function it_parses_complex_condition_with_parenthesis()
    {
        $this->parse('P1.pid IN 2, 3 AND ((P1.price >= 10 OR P1.name = x) OR P1.name CONTAINS y)')->shouldReturn(['AND' => [
            ['P1.id IN' => ['2', '3']],
            ['OR' => [
                ['OR' => [
                    ['P1.unit_price >=' => '10'],
                    ['P1.name' => 'x']
                ]],
                ['P1.name LIKE' => '%y%']
            ]]
        ]]);
    }

    public function it_parse_value()
    {
        $this->parse('P1.price > P2.price')->shouldReturn(['P1.unit_price > P2.price' => null]);
    }

    public function it_throws_exception_for_incorrect_query()
    {
        $this->shouldThrow(new QueryEvaluatorException(['placeholder' => 'pid'], QueryEvaluatorException::UNKNOWN_PREFIX))->duringParse('pid = 10');
    }
}
