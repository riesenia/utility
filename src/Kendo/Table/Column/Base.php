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
 * Base class for Table helper columns.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Base
{
    /**
     * Type used for model type property.
     *
     * @var string
     */
    protected $_type = 'string';

    /**
     * Column template with %format% / %field% / %class% placeholder.
     *
     * @var string
     */
    protected $_template = '<td class="%class%" style="%style%"><div>%format%</div></td>';

    /**
     * Field format with %field% placeholder.
     *
     * @var string
     */
    protected $_format = '#: %field% #';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn';

    /**
     * Predefined style.
     *
     * @var string
     */
    protected $_style = '#: grid.columns[grid.element.find("th[data-field=%field%]").data("index")].hidden ? "display: none;" : "" #';

    /**
     * Options.
     *
     * @var array<string,mixed>
     */
    protected $_options = [];

    /**
     * Model options.
     *
     * @var array<string,mixed>
     */
    protected $_modelOptions = [];

    /**
     * Table id.
     *
     * @var string
     */
    protected $_tableId;

    /**
     * Not available condition.
     *
     * @var string
     */
    protected $_notAvailableCondition = '%field% !== null && %field% !== ""';

    /**
     * Not available text.
     *
     * @var string
     */
    protected $_notAvailableText = 'N/A';

    /**
     * Construct the column.
     *
     * @param array<string,mixed> $options
     * @param string              $tableId
     */
    public function __construct(array $options, string $tableId)
    {
        $this->_tableId = $tableId;

        // model options first
        if (isset($options['model'])) {
            $this->_modelOptions = $options['model'];
            unset($options['model']);
        }

        // N/A condition
        if (isset($options['condition'])) {
            $this->_notAvailableCondition = $options['condition'];
        }

        $this->_options = $options;

        // class
        $this->_options['class'] = isset($this->_options['class']) ? $this->_class . ' ' . $this->_options['class'] : $this->_class;

        // style
        $this->_style = \str_replace('%field%', $this->_options['field'], $this->_style);
        $this->_options['style'] = isset($this->_options['style']) ? $this->_style . ' ' . $this->_options['style'] : $this->_style;

        // header style and class
        if (!isset($this->_options['headerAttributes']['class'])) {
            $this->_options['headerAttributes']['class'] = $this->_options['class'];
        }

        if (!isset($this->_options['headerAttributes']['style'])) {
            $this->_options['headerAttributes']['style'] = $this->_options['style'];
        }

        // template
        if (isset($this->_options['template'])) {
            $this->_template = \str_replace('%format%', $this->_options['template'], $this->_template);
        }

        // type
        if (!isset($this->_modelOptions['type'])) {
            $this->_modelOptions['type'] = $this->_type;
        }
    }

    /**
     * Get options for model field definition.
     *
     * @return array<string,mixed>
     */
    public function getModelOptions(): array
    {
        return $this->_modelOptions;
    }

    /**
     * Get options for grid column definition.
     *
     * @return array<string,mixed>
     */
    public function getColumnOptions(): array
    {
        return $this->_options;
    }

    /**
     * Return rendered javascript.
     *
     * @return string
     */
    public function script(): string
    {
        $script = '';

        // hide under certain width
        if (isset($this->_options['display'])) {
            $script .= '$(window).resize(function(e) {
                if ($("#' . $this->_tableId . '").width() < ' . (int) $this->_options['display'] . ') {
                    $("#' . $this->_tableId . '").data("kendoGrid").hideColumn("' . $this->_options['field'] . '");
                } else {
                    $("#' . $this->_tableId . '").data("kendoGrid").showColumn("' . $this->_options['field'] . '");
                }
            });';
        }

        return $script;
    }

    public function __toString(): string
    {
        $format = $this->_format;

        // link
        if (isset($this->_options['link']) && $this->_options['link']) {
            if (!\is_array($this->_options['link'])) {
                $this->_options['link'] = ['href' => $this->_options['link']];
            }

            // build link template
            $link = '<a';

            foreach ($this->_options['link'] as $attribute => $value) {
                $link .= ' ' . $attribute . '="' . $value . '"';
            }

            $link .= '>';

            // link condition
            if (isset($this->_options['link_condition']) && $this->_options['link_condition']) {
                $format = '# if (' . $this->_options['link_condition'] . ') { #' . $link . $format . '</a># } else { # ' . $format . ' # } #';
            } else {
                $format = $link . $format . '</a>';
            }
        }

        // print N/A for empty columns
        $format = '# if (' . $this->_notAvailableCondition . ') { #' . $format . '# } else { # ' . $this->_notAvailableText . ' # } #';

        return \str_replace(['%format%', '%field%', '%class%', '%style%'], [$format, $this->_options['field'], $this->_options['class'], $this->_options['style']], $this->_template);
    }
}
