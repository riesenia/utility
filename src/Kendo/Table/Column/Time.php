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
 * Time column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Time extends Date
{
    /**
     * Field format with %field% placeholder.
     *
     * @var string
     */
    protected $_format = '#: kendo.toString(%field%, "t") #';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn tableDate tableTime';
}
