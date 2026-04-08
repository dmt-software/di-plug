<?php

declare(strict_types=1);

namespace DMT\DependencyInjection;

use Closure;
use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Attributes\ConfigValue;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use DMT\DependencyInjection\Traits\HasContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;
use Throwable;

class Container implements ContainerInterface
{
    private array $lookups = [];

    public function __construct(private readonly Adapter $adapter)
    {
        $this->lookups = [
            $this->getExistingValueForParameter(...),
            $this->getValueFromConfiguration(...),
            $this->getValueFromContainer(...),
            $this->getValueFromParameterDefault(...),
        ];
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
     * @template T
     * @param string|class-string<T> $id
     * @param mixed ...$args
     * @return ($id is class-string<T> ? T : mixed)
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get(string $id, mixed ...$args)
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
     * @template T
     * @param string|class-string<T> $className
     * @param mixed ...$constructorArgs
     * @return T
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function getInstance(string $className, mixed ...$constructorArgs): object
    {
        try {
            $class = new ReflectionClass($className);
            if (!$class->isInstantiable()) {
                throw new RuntimeException('can not instantiate class');
            }

            $constructor = $class->getConstructor();
            if ($constructor && count($constructorArgs) <> $constructor->getNumberOfParameters()) {
                $constructorArgs = $this->getConstructorArgs($constructor, $constructorArgs);
            }

            return $class->newInstance(...$constructorArgs);
        } catch (Throwable $exception) {
            UnresolvedException::throwException($className, $exception);
        }
    }

    private function getConstructorArgs(ReflectionMethod $constructor, array $constructorArgs): array
    {
        $parameters = $constructor->getParameters();

        $arguments = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $value = null;

            foreach ($this->lookups as $lookup) {
                if ($value = $lookup($parameter, $constructorArgs)) {
                    $constructorArgs[$name] = $value;
                    break;
                }
            }

            $arguments[$name] = $value;
        }

        return $arguments;
    }

    private function getExistingValueForParameter(ReflectionParameter $parameter, array $constructorArgs): mixed
    {
        if (array_key_exists($parameter->getPosition(), $constructorArgs)) {
            return $constructorArgs[$parameter->getPosition()];
        }

        return $constructorArgs[$parameter->getName()] ?? null;
    }


    private function getValueFromConfiguration(ReflectionParameter $parameter, array $constructorArgs): mixed
    {
        if (array_key_exists($parameter->getName(), $constructorArgs)) {
            return $constructorArgs[$parameter->getName()];
        }

        if (!$this->has(ConfigurationInterface::class)) {
            return null;
        }

        $attributes = $parameter->getAttributes(ConfigValue::class);

        if (!$attributes) {
            return null;
        }

        /** @var ConfigValue $attribute */
        $attribute = $attributes[0]->newInstance();

        return $this->get(ConfigurationInterface::class)->get(
            $attribute->entry,
            $attribute->defaultValue,
        );
    }

    private function getValueFromContainer(ReflectionParameter $parameter, array $constructorArgs): mixed
    {
        if (array_key_exists($parameter->getName(), $constructorArgs)) {
            return $constructorArgs[$parameter->getName()];
        }

        if (!$parameterType = $parameter->getType()) {
            return null;
        }

        if (!class_exists($parameterType->getName()) && !interface_exists($parameterType->getName())) {
            return null;
        }

        return $this->get($parameterType->getName());
    }

    private function getValueFromParameterDefault(ReflectionParameter $parameter, array $constructorArgs): mixed
    {
        if (array_key_exists($parameter->getName(), $constructorArgs)) {
            return $constructorArgs[$parameter->getName()];
        }

        if (!$parameter->isDefaultValueAvailable()) {
            throw new RuntimeException('Could not resolve constructor argument');
        }

        return $parameter->getDefaultValue();
    }
}
