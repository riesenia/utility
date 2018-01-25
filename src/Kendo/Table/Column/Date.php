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
    /**
     * Type used for model type property.
     *
     * @var string
     */
    protected $_type = 'date';

    /**
     * Field format with %field% placeholder.
     *
     * @var string
     */
    protected $_format = '#: kendo.toString(%field%, "d") #';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn tableDate';
}
