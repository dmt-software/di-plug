<?php

namespace DMT\Test\DependencyInjection\Fixtures;

use Psr\Container\ContainerInterface;

class DummyPsrContainer implements ContainerInterface
{
    private object $container;
    protected string $containerName;
    public object $publicContainer;

    /**
     * DummyPsrContainer constructor.
     * @param object $container
     */
    public function __construct(object $container)
    {
        $this->container = $container;
        $this->publicContainer = $container;
        $this->containerName = get_class($container);
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->exists($id);
    }

    public function getInnerContainer(): object
    {
        return $this->container;
    }
}
