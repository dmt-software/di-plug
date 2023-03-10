<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Resolvers;

use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;

/**
 * Class PropertyResolver
 *
 * This resolver returns a container instance that is wrapped within another object.
 */
final class PropertyResolver implements ResolverInterface
{
    public function __construct(private readonly object $containerInstance, private readonly string $propertyName)
    {
    }

    /**
     * Get the inner container.
     *
     * @return object
     * @throws InvalidArgumentException
     */
    public function resolve(): object
    {
        try {
            $reflectionProperty = new ReflectionProperty($this->containerInstance, $this->propertyName);
            $value = $reflectionProperty->getValue($this->containerInstance);

            if (!is_object($value)) {
                throw new InvalidArgumentException("property `{$this->propertyName}` is not a container instance");
            }

            return $value;
        } catch (ReflectionException) {
            throw new InvalidArgumentException("property `{$this->propertyName}` does not exists");
        }
    }
}
