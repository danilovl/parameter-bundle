<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Exception;

class EmptyParameterKeyException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Parameter key cannot be empty');
    }
}
