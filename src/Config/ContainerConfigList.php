<?php

namespace DMT\DependencyInjection\Config;

use ArrayIterator;

/**
 * Class ContainerConfigIterator
 *
 * @package DMT\DependencyInjection\Config
 */
final class ContainerConfigList extends ArrayIterator
{
    /**
     * ContainerConfigIterator constructor.
     * @param array $containerConfigList
     */
    public function __construct(array $containerConfigList)
    {
        array_walk($containerConfigList, function (array &$containerConfig, string $className) {
            $containerConfig = ContainerConfig::create($className, $containerConfig);
        });

        parent::__construct($containerConfigList);
    }
}
