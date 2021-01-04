<?php

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use League\Container\Container;
use League\Container\Exception\NotFoundException as LeagueNotFoundException;

class LeagueAdapter extends Adapter
{
    private Container $container;

    /**
     * LeagueAdapter constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @param Closure $value
     */
    public function set(string $id, Closure $value): void
    {
        if ($this->container->has($id)) {
            UnavailableException::throwException($id);
        }

        $this->container->add($id, $value->bindTo($this));
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        try {
            return $this->container->get($id);
        } catch (LeagueNotFoundException $exception) {
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
}
