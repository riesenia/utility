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
 * Input column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Input extends Base
{
    /**
     * Column template with %field%, %type% and %options% placeholder.
     *
     * @var string
     */
    protected $_format = '<input type="%type%" data-row-uid="#: uid #" name="%field%Input" value="#: %field% #"%options% />';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn tableInput';

    /**
     * Not available condition.
     *
     * @var string
     */
    protected $_notAvailableCondition = 'true';

    /**
     * Get options for grid column definition.
     *
     * @return array
     */
    public function getColumnOptions(): array
    {
        return array_diff_key($this->_options, ['input' => 1]);
    }

    /**
     * Return rendered javascript.
     *
     * @return string
     */
    public function script(): string
    {
        return parent::script() . '$("#' . $this->_tableId . '").on("change", "[name=' . $this->_options['field'] . 'Input]", function (e) {
            var dataSource = $("#' . $this->_tableId . '").data("kendoGrid").dataSource;

            // only for datasource that can be updated
            if (typeof dataSource.transport.options.update !== "undefined") {
                var item = dataSource.getByUid($(this).data("row-uid"));
                item.set("' . $this->_options['field'] . '", ' . $this->_setValue() . ');
                if (!dataSource.options.autoSync) {
                    dataSource.sync();
                }
            }
        });';
    }

    /**
     * Value setter.
     *
     * @return string
     */
    protected function _setValue(): string
    {
        return '$(this).val()';
    }

    /**
     * Return rendered column.
     *
     * @return string
     */
    public function __toString(): string
    {
        $type = 'text';
        $options = '';

        if (isset($this->_options['input'])) {
            if (isset($this->_options['input']['type'])) {
                $type = $this->_options['input']['type'];
                unset($this->_options['input']['type']);
            }

            foreach ($this->_options['input'] as $key => $value) {
                $options .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }

        return str_replace(['%type%', '%options%'], [$type, $options], parent::__toString());
    }
}
