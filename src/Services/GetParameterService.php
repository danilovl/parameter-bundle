<?php declare(strict_types=1);

namespace Danilovl\GetParameterBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GetParameterService
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
    public function getParameter(string $key)
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
    public function hasParameter(string $key)
    {
        return $this->getParameter($key) !== null;
    }
}
