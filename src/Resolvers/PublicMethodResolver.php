<?php

namespace DMT\DependencyInjection\Resolvers;

use InvalidArgumentException;

/**
 * Class PublicMethodResolver
 *
 * This resolver returns a container by calling a method on the container wrapper.
 *
 * @package DMT\DependencyInjection\Resolvers
 */
final class PublicMethodResolver implements ResolverInterface
{
    /** @var object $containerWrapper */
    private object $containerWrapper;

    /** @var string $methodName */
    private string $methodName;

    /**
     * MethodResolver constructor.
     *
     * @param object $containerWrapper
     * @param string $methodName
     */
    public function __construct(object $containerWrapper, string $methodName)
    {
        $this->containerWrapper = $containerWrapper;
        $this->methodName = $methodName;
    }

    /**
     * Get the inner container.
     *
     * @return object
     * @throws InvalidArgumentException
     */
    public function resolve(): ?object
    {
        if (!method_exists($this->containerWrapper, $this->methodName)) {
            throw new InvalidArgumentException("method `{$this->methodName}` does not exists on container wrapper");
        }

        return call_user_func([$this->containerWrapper, $this->methodName]);
    }
}
