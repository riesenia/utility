<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace spec\Riesenia\Utility\Kendo;

use PhpSpec\ObjectBehavior;

class WindowSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Riesenia\Utility\Kendo\Window');
    }

    public function it_creates_div()
    {
        $this->html()->shouldReturn('<div id="id"></div>');
    }
}
