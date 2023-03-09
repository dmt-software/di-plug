<?php
declare(strict_types=1);

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
 */
class PhpDiAdapter extends Adapter
{
    public function __construct(private readonly Container $container)
    {
    }

    public function set(string $id, Closure $value): void
    {
        if (in_array($id, $this->container->getKnownEntryNames())) {
            UnavailableException::throwException($id);
        }

        $this->container->set($id, $value->bindTo($this));
    }

    public function get(string $id)
    {
        try {
            return $this->container->get($id);
        } catch (PhpDiNotFoundException $exception) {
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
