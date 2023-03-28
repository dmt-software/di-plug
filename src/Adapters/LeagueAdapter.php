<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use League\Container\Container;
use League\Container\Exception\NotFoundException as LeagueNotFoundException;

/**
 * Class LeagueAdapter
 *
 * @see https://github.com/thephpleague/container
 */
class LeagueAdapter extends Adapter
{
    public function __construct(private readonly Container $container)
    {
    }

    public function set(string $id, Closure $value): void
    {
        if ($this->container->has($id)) {
            UnavailableException::throwException($id);
        }

        $this->container->add($id, $value);
    }

    public function get(string $id)
    {
        try {
            return $this->container->get($id);
        } catch (LeagueNotFoundException $exception) {
            NotFoundException::throwException($id, $exception);
        } catch (Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}
