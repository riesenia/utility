<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Table checkbox column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class TableCheckbox extends Base
{
    /**
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="%class%"><input type="checkbox" value="#: %field% #" name="tableCheckbox" /></td>';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableCheckbox';

    /**
     * Construct the column
     *
     * @param array options
     * @param string table id
     */
    public function __construct(array $options, $tableId)
    {
        $options['filterable'] = false;
        $options['sortable'] = false;

        $options['headerTemplate'] = '<input type="checkbox" value="#: %field% #" name="tableCheckboxAll" />';

        parent::__construct($options, $tableId);

        // default field is id
        if (!isset($this->_options['field'])) {
            $this->_options['field'] = 'id';
        }
    }

    /**
     * Return rendered javascript
     *
     * @return string
     */
    public function script()
    {
        return parent::script() . '$("#' . $this->_tableId . '").on("change", "[name=tableCheckboxAll]", function (e) {
            var dataSource = $("#' . $this->_tableId . '").data("kendoGrid").dataSource;

            $("#' . $this->_tableId . ' [name=tableCheckbox]").prop("checked", $(this).prop("checked"));
        });
        $("#' . $this->_tableId . '").data("kendoGrid").bind("dataBound", function(e) {
            if ($("#' . $this->_tableId . ' [name=tableCheckboxAll]").prop("checked")) {
                $("#' . $this->_tableId . ' [name=tableCheckbox]").prop("checked", true);
            }
        });';
    }
}
