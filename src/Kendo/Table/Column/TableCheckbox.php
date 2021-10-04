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
 * Table checkbox column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class TableCheckbox extends Base
{
    /** @var string */
    protected $_template = '<td class="%class%" style="%style%"><div><input type="checkbox" value="#: %field% #" name="tableCheckbox" /></div></td>';

    /** @var string */
    protected $_class = 'tableColumn tableCheckbox tableCheckbox--main';

    /** @var string */
    protected $_style = '';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $options, string $tableId)
    {
        $options['filterable'] = false;
        $options['sortable'] = false;

        $options['headerTemplate'] = isset($options['selectAll']) && !$options['selectAll'] ? '' : '<input type="checkbox" value="#: %field% #" name="tableCheckboxAll" />';

        parent::__construct($options, $tableId);

        // default field is id
        if (!isset($this->_options['field'])) {
            $this->_options['field'] = 'id';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function script(): string
    {
        return parent::script() . '$("#' . $this->_tableId . '").on("change", "[name=tableCheckboxAll]", function (e) {
            $("#' . $this->_tableId . ' [name=tableCheckbox]").prop("checked", $(this).prop("checked")).prop("disabled", $(this).prop("checked"));
        });
        $("#' . $this->_tableId . '").data("kendoGrid").bind("dataBound", function(e) {
            if ($("#' . $this->_tableId . ' [name=tableCheckboxAll]").prop("checked")) {
                $("#' . $this->_tableId . ' [name=tableCheckbox]").prop("checked", true).prop("disabled", true);
            }
        });';
    }
}
