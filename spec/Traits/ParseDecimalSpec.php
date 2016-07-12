<?php
namespace spec\Riesenia\Utility\Traits;

use PhpSpec\ObjectBehavior;
use Riesenia\Utility\Traits\ParseDecimalTrait;

class ParseDecimalSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('spec\Riesenia\Utility\Traits\ParseDecimalClass');
    }

    public function it_correctly_parses_float()
    {
        $this->parseDecimal(47)->shouldReturn(47.0);
        $this->parseDecimal(2.2)->shouldReturn(2.2);
        $this->parseDecimal('3.24')->shouldReturn(3.24);
        $this->parseDecimal('53,04')->shouldReturn(53.04);
        $this->parseDecimal('1 253')->shouldReturn(1253.0);
        $this->parseDecimal('1 987 253,349')->shouldReturn(1987253.349);
    }

    public function it_correctly_parses_float_with_options()
    {
        $this->parseDecimal('1,253', ['thousands_separator' => ','])->shouldReturn(1253.0);
        $this->parseDecimal('1.987.253,349', ['thousands_separator' => '.'])->shouldReturn(1987253.349);
    }
}

class ParseDecimalClass
{
    use ParseDecimalTrait;

    public function parseDecimal($number, array $options = [])
    {
        return $this->_parseDecimal($number, $options);
    }
}
