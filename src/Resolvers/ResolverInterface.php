<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Resolvers;

use InvalidArgumentException;
use RuntimeException;

/**
 * Interface ResolverInterface
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
