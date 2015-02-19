<?php
namespace Riesenia\Utility\Kendo\Table\Action;

use Riesenia\Utility\Kendo\Table;

/**
 * Base class for Table helper actions
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Base
{
    /**
     * Action template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<a class="btn btn-default %class%" href="%link%" title="%title%"><span class="glyphicon glyphicon-%icon%"></span></a>';

    /**
     * Options
     *
     * @var array
     */
    protected $_options = [
        'class' => '',
        'link' => '\#',
        'title' => '',
        'icon' => 'question-sign'
    ];

    /**
     * Reference to table
     *
     * @var \Riesenia\Utility\Kendo\Table
     */
    protected $_table;

    /**
     * Construct action
     *
     * @param array options
     * @param \Riesenia\Utility\Kendo\Table
     */
    public function __construct(array $options, Table $table)
    {
        $this->_table = $table;
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * Column defintion in a grid row template
     *
     * @return string
     */
    public function __toString()
    {
        return str_replace(['%class%', '%link%', '%title%', '%icon%'], [$this->_options['class'], $this->_options['link'], $this->_options['title'], $this->_options['icon']], $this->_template);
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

    /**
     * Command (for column definition)
     *
     * @return mixed
     */
    public function command()
    {
        return false;
    }
}
