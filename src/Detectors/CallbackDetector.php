<?php

namespace DMT\DependencyInjection\Detectors;

use Closure;
use DMT\DependencyInjection\Resolvers\ResolverInterface;

class CallbackDetector implements DetectorInterface
{
    private readonly Closure $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback(...);
    }

    public function detect(?object $containerInstance): ?ResolverInterface
    {
        return call_user_func($this->callback, $containerInstance);
    }
}
