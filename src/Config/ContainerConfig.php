<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Config;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;
use InvalidArgumentException;

final class ContainerConfig
{
    public function __construct(
        public readonly string $className,
        public string $resolver,
        public string $adapter,
        public ?string $accessor = null
    ) {
        assert(is_a($resolver, ResolverInterface::class, true), new InvalidArgumentException('Invalid resolver used'));
        assert(is_a($adapter, Adapter::class, true), new InvalidArgumentException('Invalid adapter used'));
    }

    public static function create(string $className, array $arguments = []): self
    {
        $arguments += ['className' => $className];
        $arguments['resolver'] ??= Resolver::class;

        return new ContainerConfig(...array_replace(get_class_vars(__CLASS__), $arguments));
    }
}
