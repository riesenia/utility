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
     * Construct the picker
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->_widget = Kendo::createDateTimePicker('#' . $id);

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
        // range from
        if ($this->_rangeFrom) {
            $rangeCode = '$("#' . $this->_rangeFrom . '").data("' . $this->_widget->name() . '").max(this.value() ? this.value() : this.max());';
        }

        // range from
        if ($this->_rangeTo) {
            $rangeCode = '$("#' . $this->_rangeTo . '").data("' . $this->_widget->name() . '").min(this.value() ? this.value() : this.min());';
        }

        $this->_widget->setChange(Kendo::js('function(e) {
            // date for hidden field
            var value = this.value();
            $("#' . $this->_id . '-hidden").val(value ? kendo.date.addDays(value, value.getTimezoneOffset() / -1440).toISOString().substring(0, 19).replace("T", " ") : "");
            ' . @$rangeCode . '
        }'));

        $script = $this->_widget->__toString();

        $script .= 'setTimeout(function() {
            if ($("#' . $this->_id . '").length) {
                $("#' . $this->_id . '").data("' . $this->_widget->name() . '").trigger("change");
            }
        }, 1);';

        return $script;
    }
}
