<?php

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Pimple\Container;

/**
 * Class PimpleAdapter
 *
 * @see https://github.com/silexphp/Pimple
 *
 * @package DMT\DependencyInjection\Adapters
 */
final class PimpleAdapter extends Adapter
{
    private Container $container;

    /**
     * PimpleAdapter constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add an entry to the container.
     *
     * @param string $id
     * @param Closure $value
     */
    public function set(string $id, Closure $value): void
    {
        try {
            $this->container[$id] = $value->bindTo($this);
        } catch (\Exception $exception) {
            UnavailableException::throwException($id, $exception);
        }
    }

    /**
     * Find an entry in container.
     *
     * @param string $id
     * @return object
     */
    public function get($id): object
    {
        if (!isset($this->container[$id])) {
            NotFoundException::throwException($id);
        }

        try {
            return $this->container[$id];
        } catch (\Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    /**
     * Check if an entry exists in container.
     *
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->container[$id]);
    }
}
