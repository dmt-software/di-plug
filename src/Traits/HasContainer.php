<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Traits;

use Psr\Container\ContainerInterface;

trait HasContainer
{
    private ContainerInterface $_container_; // phpcs:ignore

    public function getContainer(): ContainerInterface
    {
        return $this->_container_;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->_container_ = $container;
    }
}
