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
 * DateTime helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class DateTime extends Date
{
    /**
     * Substring of datetime end.
     *
     * @var int
     */
    protected $_substringEnd = 19;

    /**
     * Construct the picker.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct($id);

        $this->widget = Kendo::createDateTimePicker('#' . $id);
    }
}
