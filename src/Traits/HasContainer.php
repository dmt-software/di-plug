<?php

namespace DMT\DependencyInjection\Traits;

use Psr\Container\ContainerInterface;

trait HasContainer
{
    private ContainerInterface $_container;

    public function getContainer(): ContainerInterface
    {
        return $this->_container;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->_container = $container;
    }
}
