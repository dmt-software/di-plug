<?php

namespace DMT\DependencyInjection\Adapters;

use Aura\Di\Container;
use Aura\Di\Exception\ServiceNotFound;
use Closure;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use ReflectionException;
use ReflectionProperty;

/**
 * Class AuraAdapter
 *
 * NOTE: When container is locked, it will be unlocked, but it is recommended to initiate this before any dependency is
 * resolved.
 *
 * @see https://github.com/auraphp/Aura.Di
 *
 * @package DMT\DependencyInjection\Adapters
 */
final class AuraAdapter extends Adapter
{
    private Container $container;

    /**
     * AuraAdapter constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        if ($container->isLocked()) {
            $this->unlock();
        }
    }

    /**
     * @param string $id
     * @param Closure $value
     */
    public function set(string $id, Closure $value): void
    {
        try {
            $this->container->set($id, $value->bindTo($this));
        } catch (Exception $exception) {
            UnavailableException::throwException($id, $exception);
        }
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        try {
            return $this->container->get($id);
        } catch (ServiceNotFound $exception) {
            NotFoundException::throwException($id, $exception);
        } catch (Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Unlock the container to allow a new set of dependencies.
     *
     * @throws ReflectionException
     */
    protected function unlock(): void
    {
        $reflectionProperty = new ReflectionProperty($this->container, 'locked');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->container, false);
    }
}
