<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Upload helper
 *
 * @author Milan Holes <milan@riesenia.com>
 */
class Upload extends KendoHelper
{
    /**
     * Construct the uploader
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->_widget = Kendo::createUpload('#' . $id);

        $this->addAttribute('name', $id);
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        $this->setAttribute('type', 'file');

        return $this->_input($this->_id);
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        $script = $this->_widget->__toString();

        return $script;
    }
}
