<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * DateTime helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class DateTime extends KendoHelper
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
     * Input name
     *
     * @var string
     */
    protected $_name;

    /**
     * Construct the select
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->_widget = Kendo::createDateTimePicker('#' . $id);

        $this->_name = $id;
    }

    /**
     * Set input name
     *
     * @param string name
     * @return Riesenia\Utility\Kendo\DateTime
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
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
        return '<input id="' . $this->_id . '" name="' . $this->_name . '" /><input type="hidden" id="' . $this->_id . '-hidden" name="' . $this->_name . '" />';
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        // range from
        if ($this->_rangeFrom) {
            $rangeCode = '$("#' . $this->_rangeFrom . '").data("kendoDateTimePicker").max(this.value() ? this.value() : this.max());';
        }

        // range from
        if ($this->_rangeTo) {
            $rangeCode = '$("#' . $this->_rangeTo . '").data("kendoDateTimePicker").min(this.value() ? this.value() : this.min());';
        }

        $this->_widget->setChange(Kendo::js('function(e) {
            // date for hidden field
            var value = this.value();
            if (value !== null) {
                $("#' . $this->_id . '-hidden").val(kendo.date.addDays(value, value.getTimezoneOffset() / -1440).toISOString().substring(0, 19).replace("T", " "));
            }
            ' . @$rangeCode . '
        }'));

        $script = $this->_widget->__toString();

        $script .= 'setTimeout(function() { $("#' . $this->_id . '").data("kendoDateTimePicker").trigger("change"); }, 1);';

        return $script;
    }
}
