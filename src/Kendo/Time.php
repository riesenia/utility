<?php
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
     * Construct the picker.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->widget = Kendo::createTimePicker('#' . $id);
    }
}
