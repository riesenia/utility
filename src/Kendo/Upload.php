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
     * {@inheritdoc}
     */
    public function __construct(string $id)
    {
        $this->_id = $id;

        $this->widget = Kendo::createUpload('#' . $id);

        $this->addAttribute('name', $id);
    }

    /**
     * {@inheritdoc}
     */
    public function html(): string
    {
        $this->addAttribute('type', 'file');

        return $this->_input($this->_id);
    }

    /**
     * {@inheritdoc}
     */
    public function script(): string
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
