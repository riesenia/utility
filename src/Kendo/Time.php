<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Time helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Time extends Date
{
    /**
     * Substring of datetime start.
     *
     * @var int
     */
    protected $_substringStart = 11;

    /**
     * Substring of datetime end.
     *
     * @var int
     */
    protected $_substringEnd = 19;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $id)
    {
        $this->_id = $id;

        $this->widget = Kendo::createTimePicker('#' . $id);
    }
}
