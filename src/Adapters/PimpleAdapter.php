<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use Pimple\Container;

/**
 * Class PimpleAdapter
 *
 * @see https://github.com/silexphp/Pimple
 */
final class PimpleAdapter extends Adapter
{
    public function __construct(private readonly Container $container)
    {
    }

    public function set(string $id, Closure $value): void
    {
        try {
            $this->container->offsetSet($id, $value);
        } catch (Exception $exception) {
            UnavailableException::throwException($id, $exception);
        }
    }

    public function get(string $id)
    {
        if (!isset($this->container[$id])) {
            NotFoundException::throwException($id);
        }

        try {
            return $this->container[$id];
        } catch (Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }
}
