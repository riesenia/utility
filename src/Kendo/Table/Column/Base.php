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
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="tableColumn">#: %field% #</td>';

    /**
     * Options
     *
     * @var array
     */
    protected $_options;

    /**
     * Construct the column
     *
     * @param string id
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Get options for model field definition
     *
     * @return array
     */
    public function getModelOptions()
    {
        return array('type' => $this->_type);
    }

    /**
     * Get options for grid column definition
     *
     * @return array
     */
    public function getColumnOptions()
    {
        return array('title' => $this->_options['title'], 'field' => $this->_options['field']);
    }

    /**
     * Column defintion in a grid row template
     *
     * @return string
     */
    public function __toString()
    {
        return str_replace('%field%', $this->_options['field'], $this->_template);
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
