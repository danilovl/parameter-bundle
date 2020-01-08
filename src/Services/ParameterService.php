<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ParameterService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
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

    /**
     * @param string $key
     * @return mixed
     */
    public function has(string $key)
    {
        return $this->get($key) !== null;
    }
}
