<?php

namespace DMT\DependencyInjection\Resolvers;

use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;

/**
 * Class PropertyResolver
 *
 * This resolver returns a container instance that is wrapped within an other object.
 *
 * @package DMT\DependencyInjection\Resolvers
 */
final class PropertyResolver implements ResolverInterface
{
    /** @var object $containerWrapper */
    private object $containerWrapper;

    /** @var string $propertyName */
    private string $propertyName;

    /**
     * PropertyResolver constructor.
     *
     * @param object $containerInstance
     * @param string $propertyName
     */
    public function __construct(object $containerInstance, string $propertyName)
    {
        $this->containerWrapper = $containerInstance;
        $this->propertyName = $propertyName;
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
            $reflectionProperty = new ReflectionProperty($this->containerWrapper, $this->propertyName);

            if (!$reflectionProperty->isPublic()) {
                $reflectionProperty->setAccessible(true);
            }

            $value = $reflectionProperty->getValue($this->containerWrapper);

            if (!is_object($value)) {
                throw new InvalidArgumentException("property `{$this->propertyName}` is not a container instance");
            }

            return $value;
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException("property `{$this->propertyName}` does not exists");
        }
    }
}
