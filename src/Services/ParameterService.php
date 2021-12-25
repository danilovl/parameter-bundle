<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Services;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParameterService implements ParameterServiceInterface
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    private function getParameter(string $key, bool $ignoreNotFound = false): mixed
    {
        $keys = explode('.', $key);
        $configs = $this->parameterBag->get($keys[0]);
        array_shift($keys);

        if (empty($keys)) {
            return $configs;
        }

        foreach ($keys as $key) {
            if (isset($configs[$key])) {
                $configs = $configs[$key];
            } else {
                return $ignoreNotFound ? null : throw new ParameterNotFoundException($key);
            }
        }

        return $configs;

    }

    public function get(string $key, bool $ignoreNotFound = false): mixed
    {
        try {
            return $this->getParameter($key);
        } catch (ParameterNotFoundException $parameterNotFoundException) {
            return $ignoreNotFound ? null : throw $parameterNotFoundException;
        }
    }

    public function getString(string $key): string
    {
        return $this->get($key);
    }

    public function getInt(string $key): int
    {
        return $this->get($key);
    }

    public function getFloat(string $key): float
    {
        return $this->get($key);
    }

    public function getBoolean(string $key): bool
    {
        return $this->get($key);
    }

    public function getArray(string $key): array
    {
        return $this->get($key);
    }

    public function has(string $key): bool
    {
        try {
            $this->get($key);

            return true;
        } catch (ParameterNotFoundException) {
            return false;
        }
    }
}
