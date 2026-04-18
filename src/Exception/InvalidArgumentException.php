<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Exception;

class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @param string $message The error message
     * @param int $code The error code
     */
    public function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
