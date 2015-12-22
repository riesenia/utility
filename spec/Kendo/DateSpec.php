<?php
namespace spec\Riesenia\Utility\Kendo;

use PhpSpec\ObjectBehavior;

class DateSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Riesenia\Utility\Kendo\Date');
    }

    public function it_creates_input_with_hidden_input()
    {
        $this->html()->shouldReturn('<input name="id" id="id" /><input name="id" type="hidden" id="id-hidden" />');
    }
}
