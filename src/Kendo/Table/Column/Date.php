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
 * Date column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Date extends Base
{
    /** @var string */
    protected $_type = 'date';

    /** @var string */
    protected $_format = '#: kendo.toString(%field%, "d") #';

    /** @var string */
    protected $_class = 'tableColumn tableDate';
}
