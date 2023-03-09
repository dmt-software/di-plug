<?php

namespace DMT\DependencyInjection;

use Closure;
use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use DMT\DependencyInjection\Traits\HasContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use RuntimeException;
use Throwable;

class Container implements ContainerInterface
{
    public function __construct(private readonly Adapter $adapter)
    {
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
     * @param mixed ...$args
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get(string $id, mixed...$args)
    {
        $dependency = call_user_func(
            function () use ($id, $args) {
                if (class_exists($id) && (count($args) || !$this->has($id))) {
                    return $this->getInstance($id, ...$args);
                }

                return $this->adapter->get($id);
            }
        );

        if (is_object($dependency)) {
            foreach (class_uses($dependency) as $trait) {
                $traits = class_uses($trait) + [$trait];
                if (in_array(HasContainer::class, $traits)) {
                    /** @var HasContainer $dependency */
                    $dependency->setContainer($this);
                }
            }
        }

        return $dependency;
    }

    /**
     * Check if an entry exists in container.
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->adapter->has($id);
    }

    /**
     * Register dependencies by a service provider.
     *
     * @param ServiceProviderInterface $provider
     */
    public function register(ServiceProviderInterface $provider): void
    {
        $provider->register($this);
    }

    /**
     * Get in instance of a class
     *
     * @param string $className
     * @param mixed ...$constructorArgs
     *
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function getInstance(string $className, mixed...$constructorArgs): object
    {
        try {
            $class = new ReflectionClass($className);
            if (!$class->isInstantiable()) {
                throw new RuntimeException('can not instantiate class');
            }

            return $class->newInstance(...$constructorArgs);
        } catch (Throwable $exception) {
            UnresolvedException::throwException($className, $exception);
        }
    }
}
