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

class DateTimeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('id');
    }

    public function it_is_initializable_and_extends_date()
    {
        $this->shouldHaveType('Riesenia\Utility\Kendo\DateTime');
        $this->shouldHaveType('Riesenia\Utility\Kendo\Date');
    }
}
