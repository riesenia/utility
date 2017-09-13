<?php
namespace Riesenia\Utility\Condition;

class QueryEvaluatorException extends \RuntimeException
{
    // exception codes
    const MISSING_OPENING_PARENTHESIS = 701;
    const MISSING_CLOSING_PARENTHESIS = 702;
    const UNKNOWN_PLACEHOLDER = 703;
    const UNKNOWN_OPERATOR = 704;
    const INVALID_CONDITION = 705;
    const MISSING_PREFIX = 706;

    /**
     * Array of attributes that are passed in from the constructor
     *
     * @var array
     */
    protected $_attributes = [];

    /**
     * Constructor
     *
     * @param string|array error message
     * @param int code
     * @param \Throwable previous exception
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        if (is_array($message)) {
            $this->_attributes = $message;
            $message = "Invalid query!";
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the passed in attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }
}
