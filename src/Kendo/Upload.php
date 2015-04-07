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
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return '<input id="' . $this->_id . '" name="' . $this->_id . '" type="file" />';
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
