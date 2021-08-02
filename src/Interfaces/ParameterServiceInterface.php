<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Interfaces;

interface ParameterServiceInterface
{
    public function get(string $key): mixed;

    public function has(string $key): bool;
}
