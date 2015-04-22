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
     * Input name
     *
     * @var string
     */
    protected $_name;

    /**
     * Construct the uploader
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->_widget = Kendo::createUpload('#' . $id);
        $this->_name = $id;
    }

    /**
     * Set input name
     *
     * @param string name
     * @return Riesenia\Utility\Kendo\Upload
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return '<input id="' . $this->_id . '" name="' . $this->_name . '" type="file" />';
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
