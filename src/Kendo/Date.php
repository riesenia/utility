<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Date helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Date extends KendoHelper
{
    /**
     * Range "from" element id
     *
     * @var string
     */
    protected $_rangeFrom;

    /**
     * Range "to" element id
     *
     * @var string
     */
    protected $_rangeTo;

    /**
     * Substring of datetime start
     *
     * @var int
     */
    protected $_substringStart = 0;

    /**
     * Substring of datetime end
     *
     * @var int
     */
    protected $_substringEnd = 10;

    /**
     * Construct the picker
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->widget = Kendo::createDatePicker('#' . $id);

        $this->addAttribute('name', $id);
    }

    /**
     * Set range "from" element id
     *
     * @param string id of the "from" element
     * @return Riesenia\Utility\Kendo\DateTime
     */
    public function rangeFrom($id)
    {
        $this->_rangeFrom = $id;

        return $this;
    }

    /**
     * Set range "to" element id
     *
     * @param string id of the "to" element
     * @return Riesenia\Utility\Kendo\DateTime
     */
    public function rangeTo($id)
    {
        $this->_rangeTo = $id;

        return $this;
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        $html = $this->_input($this->_id);
        $this->addAttribute('type', 'hidden');

        return $html . $this->_input($this->_id . '-hidden');
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        $rangeCode = '';

        // range from
        if ($this->_rangeFrom) {
            $rangeCode = '$("#' . $this->_rangeFrom . '").data("' . $this->widget->name() . '").max(this.value() ? this.value() : this.max());';
        }

        // range from
        if ($this->_rangeTo) {
            $rangeCode = '$("#' . $this->_rangeTo . '").data("' . $this->widget->name() . '").min(this.value() ? this.value() : this.min());';
        }

        $this->widget->setChange(Kendo::js('function(e) {
            // date for hidden field
            var value = this.value();
            $("#' . $this->_id . '-hidden").val(value ? kendo.date.addDays(value, value.getTimezoneOffset() / -1440).toISOString().substring(' . $this->_substringStart . ', ' . $this->_substringEnd . ').replace("T", " ") : "");
            ' . $rangeCode . '
        }'));

        $script = $this->widget->__toString();

        $script .= 'setTimeout(function() {
            if ($("#' . $this->_id . '").length) {
                $("#' . $this->_id . '").data("' . $this->widget->name() . '").trigger("change");
            }
        }, 1);';

        return $script;
    }
}
