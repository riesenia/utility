<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Date helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Date extends DateTime
{
    /**
     * Construct the picker
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->_widget = Kendo::createDatePicker('#' . $id);
    }
}
