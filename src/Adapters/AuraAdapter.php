<?php

declare(strict_types=1);

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
 */
final class AuraAdapter extends Adapter
{
    public function __construct(private readonly Container $container)
    {
        if ($container->isLocked()) {
            $this->unlock();
        }
    }

    public function set(string $id, Closure $value): void
    {
        try {
            $this->container->set($id, $value->bindTo($this));
        } catch (Exception $exception) {
            UnavailableException::throwException($id, $exception);
        }
    }

    public function get(string $id)
    {
        try {
            return $this->container->get($id);
        } catch (ServiceNotFound $exception) {
            NotFoundException::throwException($id, $exception);
        } catch (Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    private function unlock(): void
    {
        $reflectionProperty = new ReflectionProperty($this->container, 'locked');
        $reflectionProperty->setValue($this->container, false);
    }
}
