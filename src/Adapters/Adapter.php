<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use Psr\Container\ContainerInterface;

abstract class Adapter implements ContainerInterface
{
    /**
     * Add an entry to the container.
     *
     * @param string $id
     * @param Closure $value
     * @throws UnavailableException
     */
    abstract public function set(string $id, Closure $value): void;
}
