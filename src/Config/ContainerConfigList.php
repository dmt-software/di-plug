<?php

namespace DMT\DependencyInjection\Config;

use ArrayIterator;

final class ContainerConfigList extends ArrayIterator
{
    public function __construct(array $containerConfigList)
    {
        array_walk($containerConfigList, function (array &$containerConfig, string $className) {
            $containerConfig = ContainerConfig::create($className, $containerConfig);
        });

        parent::__construct($containerConfigList);
    }
}
