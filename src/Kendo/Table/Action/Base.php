<?php
namespace Riesenia\Utility\Kendo\Table\Action;

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
    protected $_template = '<a class="btn btn-default %class%" href="%link%" title="%title%" target="%target%"><span class="glyphicon glyphicon-%icon%"></span></a>';

    /**
     * Options
     *
     * @var array
     */
    protected $_options = [
        'class' => '',
        'link' => '\#',
        'title' => '',
        'target' => '_self',
        'icon' => 'question-sign'
    ];

    /**
     * Table id
     *
     * @var string
     */
    protected $_tableId;

    /**
     * Construct action
     *
     * @param array options
     * @param string
     */
    public function __construct(array $options, $tableId)
    {
        $this->_tableId = $tableId;
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * Column defintion in a grid row template
     *
     * @return string
     */
    public function __toString()
    {
        $template = $this->_template;

        // condition
        if (isset($this->_options['condition'])) {
            $template = '# if (' . $this->_options['condition'] . ') { #' . $template . '# } #';
        }

        return str_replace(['%class%', '%link%', '%title%', '%icon%'], [$this->_options['class'], $this->_options['link'], $this->_options['title'], $this->_options['icon']], $template);
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
