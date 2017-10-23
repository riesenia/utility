<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Upload helper.
 *
 * @author Milan Holes <milan@riesenia.com>
 */
class Upload extends KendoHelper
{
    /**
     * Construct the uploader.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->widget = Kendo::createUpload('#' . $id);

        $this->addAttribute('name', $id);
    }

    /**
     * Return HTML.
     *
     * @return string
     */
    public function html()
    {
        $this->addAttribute('type', 'file');

        return $this->_input($this->_id);
    }

    /**
     * Return JavaScript.
     *
     * @return string
     */
    public function script()
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
