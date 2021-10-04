<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Price column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Price extends Number
{
    /** @var string */
    protected $_format = '#: kendo.toString(%field%, "c2") #';

    /** @var string */
    protected $_class = 'tableColumn tableNumber tablePrice';
}
