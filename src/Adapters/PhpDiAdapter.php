<?php

namespace DMT\DependencyInjection\Adapters;

use Closure;
use DI\Container;
use DI\NotFoundException as PhpDiNotFoundException;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;

/**
 * Class PhpDiAdapter
 *
 * @see https://github.com/PHP-DI/PHP-DI
 *
 * @package DMT\DependencyInjection\Adapters
 */
class PhpDiAdapter extends Adapter
{
    private Container $container;

    /**
     * PhpDiAdapter constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, Closure $value): void
    {
        if (in_array($id, $this->container->getKnownEntryNames())) {
            UnavailableException::throwException($id);
        }

        $this->container->set($id, $value->bindTo($this));
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        try {
            return $this->container->get($id);
        } catch (PhpDiNotFoundException $exception) {
            NotFoundException::throwException($id, $exception);
        } catch (Exception $exception) {
            UnresolvedException::throwException($id, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return $this->container->has($id);
    }
}