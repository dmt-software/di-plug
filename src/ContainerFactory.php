<?php

declare(strict_types=1);

namespace DMT\DependencyInjection;

use DMT\DependencyInjection\Adapters\AuraAdapter;
use DMT\DependencyInjection\Adapters\IlluminateAdapter;
use DMT\DependencyInjection\Adapters\LeagueAdapter;
use DMT\DependencyInjection\Adapters\PhpDiAdapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Detectors\AuraDetector;
use DMT\DependencyInjection\Detectors\DetectorInterface;
use DMT\DependencyInjection\Detectors\IlluminateDetector;
use DMT\DependencyInjection\Detectors\LeagueDetector;
use DMT\DependencyInjection\Detectors\PhpDiDetector;
use DMT\DependencyInjection\Detectors\PimpleDetector;
use DMT\DependencyInjection\Resolvers\ResolverInterface;
use RuntimeException;

final class ContainerFactory
{
    private const DETECTORS = [
        AuraAdapter::class => AuraDetector::class,
        IlluminateAdapter::class => IlluminateDetector::class,
        LeagueAdapter::class => LeagueDetector::class,
        PhpDiAdapter::class => PhpDiDetector::class,
        PimpleAdapter::class => PimpleDetector::class,
    ];

    public function __construct(private array $detectors = self::DETECTORS)
    {
        array_walk($this->detectors, fn (&$detector) => is_object($detector) || $detector = new $detector());
    }

    public function createContainer(object $containerInstance = null): Container
    {
        /** @var DetectorInterface $detector */
        foreach ($this->detectors as $adapter => $detector) {
            $resolver = $detector->detect($containerInstance);
            if ($resolver instanceof ResolverInterface) {
                return new Container(new $adapter($resolver->resolve()));
            }
        }

        throw new RuntimeException('Unsupported container');
    }
}
