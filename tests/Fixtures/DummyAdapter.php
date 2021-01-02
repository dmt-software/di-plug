<?php

namespace DMT\Test\DependencyInjection\Fixtures;

use Closure;
use DMT\DependencyInjection\Adapters\Adapter;

class DummyAdapter extends Adapter
{
    private DummyContainer $container;

    /**
     * DummyAdapter constructor.
     * @param DummyContainer $container
     */
    public function __construct(DummyContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @param Closure $value
     */
    public function set(string $id, Closure $value): void
    {
        $this->container->add($id, $value);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->container->exists($id);
    }
}