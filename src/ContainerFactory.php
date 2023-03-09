<?php

namespace DMT\DependencyInjection;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\AuraAdapter;
use DMT\DependencyInjection\Adapters\IlluminateAdapter;
use DMT\DependencyInjection\Adapters\LeagueAdapter;
use DMT\DependencyInjection\Adapters\PhpDiAdapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Config\ContainerConfigList;
use DMT\DependencyInjection\Detectors\DetectorList;
use DMT\DependencyInjection\Detectors\InstalledClassDetector;
use DMT\DependencyInjection\Detectors\InstanceOfDetector;
use DMT\DependencyInjection\Resolvers\FactoryResolver;
use DMT\DependencyInjection\Resolvers\PropertyResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use RuntimeException;

final class ContainerFactory
{
    private const DEFAULT_CONFIGURATION = [
        'supported' => [
            \Aura\Di\Container::class => [
                'adapter' => AuraAdapter::class,
                'resolver' => Resolver::class,
            ],
            \Aura\Di\ContainerBuilder::class => [
                'adapter' => AuraAdapter::class,
                'resolver' => FactoryResolver::class,
                'accessor' => 'newInstance',
            ],
            \DI\Container::class => [
                'adapter' => PhpDiAdapter::class,
                'resolver' => Resolver::class,
            ],
            \Illuminate\Container\Container::class => [
                'adapter' => IlluminateAdapter::class,
                'resolver' => Resolver::class,
            ],
            \League\Container\Container::class => [
                'adapter' => LeagueAdapter::class,
                'resolver' => Resolver::class,
            ],
            \Pimple\Container::class => [
                'adapter' => PimpleAdapter::class,
                'resolver' => Resolver::class,
            ],
            \Pimple\Psr11\Container::class => [
                'adapter' => PimpleAdapter::class,
                'resolver' => PropertyResolver::class,
                'accessor' => 'pimple',
            ],
        ],
        'detectors' => [
            InstanceOfDetector::class => [
                'supported' => [
                    \Aura\Di\Container::class,
                    \DI\Container::class,
                    \Illuminate\Container\Container::class,
                    \League\Container\Container::class,
                    \Pimple\Container::class,
                    \Pimple\Psr11\Container::class,
                ],
            ],
            InstalledClassDetector::class => [
                'supported' => [
                    \Aura\Di\ContainerBuilder::class,
                    \DI\Container::class,
                    \Illuminate\Container\Container::class,
                    \League\Container\Container::class,
                    \Pimple\Container::class,
                ]
            ],
        ],
    ];

    private ?ContainerConfigList $supportedContainers = null;

    private ?DetectorList $containerDetectors = null;

    public function __construct(array $configuration = self::DEFAULT_CONFIGURATION)
    {
        $this->supportedContainers = new ContainerConfigList($configuration['supported'] ?? []);
        $this->containerDetectors = new DetectorList($configuration['detectors'], $this->supportedContainers);
    }

    public function createContainer(object $containerInstance = null): Container
    {
        foreach ($this->containerDetectors as $detector) {
            $containerConfig = $detector->detect($containerInstance);
            if ($containerConfig) {
                return new Container($this->getAdapter($containerConfig, $containerInstance));
            }
        }

        throw new RuntimeException('Unsupported container');
    }

    private function getAdapter(ContainerConfig $config, object $containerInstance = null): Adapter
    {
        $adapter = $config->adapter;
        $resolver = $config->resolver;

        $arguments = [];
        if ($containerInstance) {
            $arguments = [$containerInstance];
            if ($config->accessor) {
                $arguments[] = $config->accessor;
            }
        }

        $resolver = new $resolver(...$arguments);

        return new $adapter($resolver->resolve());
    }
}
