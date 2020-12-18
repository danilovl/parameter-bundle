<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ParameterService
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function get(string $key): mixed
    {
        $keys = explode('.', $key);
        $configs = $this->container->getParameter($keys[0]);
        array_shift($keys);

        if (empty($keys)) {
            return $configs;
        }

        foreach ($keys as $key) {
            if (isset($configs[$key])) {
                $configs = $configs[$key];
            } else {
                return null;
            }
        }

        return $configs;
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }
}
