<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Base class for Table helper columns
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Base
{
    /**
     * Type used for model type property
     *
     * @var string
     */
    protected $_type = 'string';

    /**
     * Column template with %format% / %field% / %class% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="%class%">%format%</td>';

    /**
     * Field format with %field% placeholder
     *
     * @var string
     */
    protected $_format = '#: %field% #';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn';

    /**
     * Options
     *
     * @var array
     */
    protected $_options = [];

    /**
     * Model options
     *
     * @var array
     */
    protected $_modelOptions = [];

    /**
     * Table id
     *
     * @var string
     */
    protected $_tableId;

    /**
     * Construct the column
     *
     * @param array options
     * @param string table id
     */
    public function __construct(array $options, $tableId)
    {
        $this->_tableId = $tableId;

        // model options first
        if (isset($options['model'])) {
            $this->_modelOptions = $options['model'];
            unset($options['model']);
        }

        $this->_options = $options;

        // class
        $this->_options['class'] = isset($this->_options['class']) ? $this->_class . ' ' . $this->_options['class'] : $this->_class;
        if (!isset($this->_options['headerAttributes']['class'])) {
            $this->_options['headerAttributes']['class'] = $this->_options['class'];
        }

        // type
        if (!isset($this->_modelOptions['type'])) {
            $this->_modelOptions['type'] = $this->_type;
        }
    }

    /**
     * Get options for model field definition
     *
     * @return array
     */
    public function getModelOptions()
    {
        return $this->_modelOptions;
    }

    /**
     * Get options for grid column definition
     *
     * @return array
     */
    public function getColumnOptions()
    {
        return $this->_options;
    }

    /**
     * Return rendered column
     *
     * @return string
     */
    public function __toString()
    {
        $format = $this->_format;

        // link
        if (isset($this->_options['link']) && $this->_options['link']) {
            if (!is_array($this->_options['link'])) {
                $this->_options['link'] = ['href' => $this->_options['link']];
            }

            // build link template
            $link = '<a';

            foreach ($this->_options['link'] as $attribute => $value) {
                $link .= ' ' . $attribute . '="' . $value . '"';
            }

            $link .= '>';

            $format = $link . $format . '</a>';
        }

        // print N/A for empty columns
        $format = '# if (%field% !== null && %field% !== "") { #' . $format . '# } else { # N/A # } #';

        return str_replace(['%format%', '%field%', '%class%'], [$format, $this->_options['field'], $this->_options['class']], $this->_template);
    }

    /**
     * Return rendered javascript
     *
     * @return string
     */
    public function script()
    {
        return '';
    }
}
