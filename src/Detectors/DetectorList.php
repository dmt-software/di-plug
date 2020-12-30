<?php

namespace DMT\DependencyInjection\Detectors;

use ArrayObject;
use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Config\ContainerConfigList;
use IteratorAggregate;
use Traversable;

/**
 * Class DetectorList
 *
 * @package DMT\DependencyInjection\Detectors
 */
final class DetectorList implements IteratorAggregate
{
    /** @var DetectorInterface[] $detectors */
    private array $detectors;

    /** @var ContainerConfigList $containerConfigList */
    private ContainerConfigList $containerConfigList;

    /**
     * DetectorList constructor.
     * @param array $detectors
     * @param ContainerConfigList $containerConfigList
     */
    public function __construct(array $detectors, ContainerConfigList $containerConfigList)
    {
        $this->containerConfigList = $containerConfigList;

        array_walk($detectors, function(array &$detector, string $detectorClass) {
            $filterIterator = function (ContainerConfig $containerConfig) use ($detector) {
                return in_array($containerConfig->className, $detector['supported']);
            };

            $detector = new $detectorClass(new \CallbackFilterIterator($this->containerConfigList, $filterIterator));
        });

        $this->detectors = $detectors;
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        $this->containerConfigList->rewind();

        return new ArrayObject($this->detectors);
    }
}
