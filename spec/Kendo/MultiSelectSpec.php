<?php
namespace spec\Riesenia\Utility\Kendo;

use PhpSpec\ObjectBehavior;

class MultiSelectSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Riesenia\Utility\Kendo\MultiSelect');
    }

    public function it_creates_select()
    {
        $this->html()->shouldReturn('<select name="id" id="id"></select>');
    }
}
