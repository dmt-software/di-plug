<?php

namespace DMT\DependencyInjection\Config;

use DMT\DependencyInjection\Resolvers\Resolver;

/**
 * Class ContainerConfig
 *
 * @package DMT\DependencyInjection\Config
 */
final class ContainerConfig
{
    /**
     * The class name of the container.
     *
     * @var string $className
     */
    public string $className;

    /**
     * The class name of the container resolver.
     *
     * @var string $resolver
     */
    public string $resolver = Resolver::class;

    /**
     * The class name of the adapter for the container.
     *
     * @var string $adapter
     */
    public string $adapter;

    /**
     * The property or method to retrieve the container (if wrapped)
     *
     * @var string|null $accessor
     */
    public ?string $accessor = null;

    /**
     * ContainerConfig constructor.
     * @param string $className
     * @param string $resolver
     * @param string $adapter
     * @param string|null $accessor
     */
    public function __construct(string $className, string $resolver, string $adapter, string $accessor = null)
    {
        $this->className = $className;
        $this->resolver = $resolver;
        $this->adapter = $adapter;
        $this->accessor = $accessor;
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return static
     */
    public static function create(string $className, array $arguments = []): self
    {
        $arguments += ['className' => $className];

        return new ContainerConfig(...array_replace(get_class_vars(__CLASS__), $arguments));
    }
}
