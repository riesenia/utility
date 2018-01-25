<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

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
    public function __construct(string $id)
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
    public function html(): string
    {
        $this->addAttribute('type', 'file');

        return $this->_input($this->_id);
    }

    /**
     * Return JavaScript.
     *
     * @return string
     */
    public function script(): string
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
