<?php

namespace DMT\DependencyInjection\Resolvers;

use InvalidArgumentException;
use RuntimeException;

/**
 * Interface ResolverInterface
 *
 * @package DMT\DependencyInjection\Resolvers
 */
interface ResolverInterface
{
    /**
     * Resolve to retrieve the container.
     *
     * @return object|null
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function resolve(): ?object;
}
