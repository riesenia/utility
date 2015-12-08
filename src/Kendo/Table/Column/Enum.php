<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Enumeration column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Enum extends Base
{
    /**
     * Construct the column
     *
     * @param array options
     * @param string table id
     */
    public function __construct(array $options, $tableId)
    {
        $this->_template = '<td class="%class%">';

        if (isset($options['options'])) {
            foreach ($options['options'] as $key => $value) {
                $this->_template .= '# if (%field%.toString() == ' . json_encode($key) . ') { # ' . htmlspecialchars($value) . ' # } #';
            }
            unset($options['options']);
        }

        $this->_template .= '</td>';

        parent::__construct($options, $tableId);
    }
}
