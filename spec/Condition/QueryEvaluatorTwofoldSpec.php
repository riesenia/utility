<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

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
                    'operators' => ['>=', '>', '<', '<=']]
                ],
            'P2' => [
                'pid' => [
                    'field' => 'uuid',
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
        $this->parse('P1.pid = P2.pid')->shouldReturn(['P1.id = P2.uuid']);
        $this->parse('P1.price >= P2.price')->shouldReturn(['P1.unit_price >= P2.unit_price']);
        $this->parse('P1.name NOT P2.name')->shouldReturn(['OR' => [
            'P1.name != P2.name',
            'P1.name IS NULL'
        ]]);
        $this->parse('P1.name CONTAINS xxx.rrr')->shouldReturn(['P1.name LIKE' => '%xxx.rrr%']);
    }

    public function it_parses_and_condition()
    {
        $this->parse('P1.pid IN 2, 3 AND P1.price >= P2.price')->shouldReturn(['AND' => [
            ['P1.id IN' => ['2', '3']],
            ['P1.unit_price >= P2.unit_price']
        ]]);
    }

    public function it_parses_or_condition()
    {
        $this->parse('P2.pid IN 2, 3 OR P2.price >= P1.price')->shouldReturn(['OR' => [
            ['P2.uuid IN' => ['2', '3']],
            ['P2.unit_price >= P1.unit_price']
        ]]);
    }

    public function it_parses_and_or_condition_with_correct_precedence()
    {
        $this->parse('P1.pid IN 2, 3 AND P1.price > P2.price OR P1.name CONTAINS xxx.rrr')->shouldReturn(['OR' => [
            ['AND' => [
                ['P1.id IN' => ['2', '3']],
                ['P1.unit_price > P2.unit_price']
            ]],
            ['P1.name LIKE' => '%xxx.rrr%']
        ]]);
    }

    public function it_parses_complex_condition_with_parenthesis()
    {
        $this->parse('P1.pid NOTIN 2, 3 AND ((P1.price >= P2.price OR P1.name = x) OR P1.name CONTAINS yyy.xxx)')->shouldReturn(['AND' => [
            ['OR' => [
                'P1.id NOT IN' => ['2', '3'],
                'P1.id IS NULL'
            ]],
            ['OR' => [
                ['OR' => [
                    ['P1.unit_price >= P2.unit_price'],
                    ['P1.name' => 'x']
                ]],
                ['P1.name LIKE' => '%yyy.xxx%']
            ]]
        ]]);
    }

    public function it_throws_exception_for_incorrect_query()
    {
        $this->shouldThrow(new QueryEvaluatorException(['placeholder' => 'pid'], QueryEvaluatorException::UNKNOWN_PREFIX))->duringParse('pid = 10');
    }
}
