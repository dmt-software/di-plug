<?php

namespace DMT\Test\DependencyInjection\Fixtures;

use Psr\Container\ContainerInterface;

class DummyPsrContainer implements ContainerInterface
{
    private DummyContainer $container;
    protected string $containerName;
    public object $publicContainer;

    /**
     * DummyPsrContainer constructor.
     * @param DummyContainer $container
     */
    public function __construct(DummyContainer $container)
    {
        $this->container = $container;
        $this->publicContainer = $container;
        $this->containerName = get_class($container);
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->exists($id);
    }

    public function getInnerContainer(): object
    {
        return $this->container;
    }
}
