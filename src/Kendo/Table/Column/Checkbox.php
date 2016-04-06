<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Checkbox column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Checkbox extends Base
{
    /**
     * Type used for model type property
     *
     * @var string
     */
    protected $_type = 'boolean';

    /**
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="%class%" style="%style%"><input type="checkbox" data-row-uid="#: uid #" name="%field%Checkbox" # if (%field%) { # checked="checked" # } # /></td>';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableCheckbox';

    /**
     * Return rendered javascript
     *
     * @return string
     */
    public function script()
    {
        return parent::script() . '$("#' . $this->_tableId . '").on("change", "[name=' . $this->_options['field'] . 'Checkbox]", function (e) {
            var dataSource = $("#' . $this->_tableId . '").data("kendoGrid").dataSource;

            // only for datasource that can be updated
            if (typeof dataSource.transport.options.update !== "undefined") {
                var item = dataSource.getByUid($(this).data("row-uid"));
                item.set("' . $this->_options['field'] . '", !item.' . $this->_options['field'] . ');
                if (!dataSource.options.autoSync) {
                    dataSource.sync();
                }
            }
        });';
    }
}
