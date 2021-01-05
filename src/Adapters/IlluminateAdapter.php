<?php

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Container\EntryNotFoundException;

/**
 * Class IlluminateAdapter
 *
 * @see https://github.com/illuminate/container
 *
 * @package DMT\DependencyInjection\Adapters
 */
class IlluminateAdapter extends Adapter
{
    private Container $container;

    /**
     * IlluminateAdapter constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Store dependency in container.
     *
     * @param string $id
     * @param Closure $value
     *
     * @throws UnresolvedException
     */
    public function set(string $id, Closure $value): void
    {
        if ($this->container->has($id)) {
            UnavailableException::throwException($id);
        }

        $this->container->bind($id, $value->bindTo($this));
    }

    /**
     * Get dependency from container.
     *
     * @param string $id
     * @return object
     *
     * @throws NotFoundException
     * @throws UnresolvedException
     */
    public function get($id)
    {
        try {
            return $this->container->get($id);
        } catch (EntryNotFoundException $exception) {
            NotFoundException::throwException($id, $exception);
        } catch (Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    /**
     * Check if dependency is set.
     *
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->container->has($id);
    }
}
