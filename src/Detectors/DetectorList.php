<?php

namespace DMT\DependencyInjection\Detectors;

use ArrayObject;
use CallbackFilterIterator;
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
    private readonly array $detectors;

    /**
     * DetectorList constructor.
     *
     * @param array|DetectorInterface[] $detectors
     * @param ContainerConfigList $containerConfigList
     */
    public function __construct(array $detectors, private readonly ContainerConfigList $containerConfigList)
    {
        array_walk($detectors, function(array &$detector, string $detectorClass) {
            $detector = new $detectorClass(
                new CallbackFilterIterator(
                    $this->containerConfigList,
                    fn (ContainerConfig $configuration) => in_array($configuration->className, $detector['supported'])
                )
            );
        });

        $this->detectors = $detectors;
    }

    public function getIterator(): Traversable
    {
        $this->containerConfigList->rewind();

        return new ArrayObject($this->detectors);
    }
}
