<?php
namespace spec\Riesenia\Utility\Kendo;

use PhpSpec\ObjectBehavior;

class TreeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Riesenia\Utility\Kendo\Tree');
    }

    public function it_creates_div()
    {
        $this->html()->shouldReturn('<div id="id"></div>');
    }

    public function it_can_add_transport_directly()
    {
        $this->addTransport('read', ['url' => 'URL'])->shouldReturn($this);
        $this->dataSource->getTransport()->shouldReturn(['read' => ['url' => 'URL']]);
    }

    public function it_can_set_has_children()
    {
        $this->hasChildren()->shouldReturn($this);
        $this->model->getHasChildren()->shouldReturn('hasChildren');
    }
}
