<?php namespace UnderstandMonolog\Exception;

class HandlerException extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 0, $previous = null)
    {
        $message = 'understand-monolog: ' . $message;

        parent::__construct($message, $code, $previous);
    }
}