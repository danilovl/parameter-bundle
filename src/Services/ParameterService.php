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

    public function get(string $key): mixed
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
                throw new ParameterNotFoundException($key);
            }
        }

        return $configs;
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
