<?php

namespace DMT\DependencyInjection;

use Closure;
use DMT\DependencyInjection\Adapters\Adapter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Container
 *
 * @package DMT\DependencyInjection
 */
class Container implements ContainerInterface
{
    private Adapter $adapter;

    /**
     * Container constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Add an entry to the container.
     *
     * @param string $id
     * @param Closure $value
     * @throws ContainerExceptionInterface
     */
    public function set(string $id, Closure $value): void
    {
        $this->adapter->set($id, $value);
    }

    /**
     * Find an entry in container.
     *
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        return $this->adapter->get($id);
    }

    /**
     * Check if an entry exists in container.
     *
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->adapter->has($id);
    }
}
