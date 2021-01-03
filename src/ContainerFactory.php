<?php

namespace DMT\DependencyInjection;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Config\ContainerConfigList;
use DMT\DependencyInjection\Detectors\DetectorList;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ContainerFactory
 *
 * @package DMT\DependencyInjection
 */
final class ContainerFactory
{
    /** @var ContainerConfigList|null */
    private ?ContainerConfigList $supportedContainers = null;

    /** @var DetectorList|null */
    private ?DetectorList $containerDetectors = null;

    /**
     * Create the container from settings.
     *
     * @param object|null $containerInstance
     * @return Container
     */
    public function createContainer(object $containerInstance = null): Container
    {
        if (!$this->supportedContainers) {
            $this->parseConfig();
        }

        foreach ($this->containerDetectors as $detector) {
            $containerConfig = $detector->detect($containerInstance);
            if ($containerConfig) {
                return new Container($this->getAdapter($containerConfig, $containerInstance));
            }
        }

        throw new \RuntimeException('Unsupported container');
    }

    /**
     * Parse the configuration.
     *
     * @param string $file The yml containing the config.
     */
    public function parseConfig(string $file = __DIR__ . '/../config/container.yml'): void
    {
        $configuration = Yaml::parseFile($file);

        $this->supportedContainers = new ContainerConfigList($configuration['supported'] ?? []);
        $this->containerDetectors = new DetectorList($configuration['detectors'], $this->supportedContainers);
    }

    /**
     * @param ContainerConfig $config
     * @param object|null $containerInstance
     * @return Adapter
     */
    protected function getAdapter(ContainerConfig $config, object $containerInstance = null): Adapter
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
