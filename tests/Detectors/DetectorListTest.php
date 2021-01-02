<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Config\ContainerConfigList;
use DMT\DependencyInjection\Detectors\DetectorList;
use DMT\DependencyInjection\Detectors\InstalledClassDetector;
use DMT\DependencyInjection\Detectors\InstanceOfDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\Test\DependencyInjection\Fixtures\DummyAdapter;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use PHPUnit\Framework\TestCase;
use Pimple\Container as PimpleContainer;

class DetectorListTest extends TestCase
{
    public function testDetectors()
    {
        $list = new ContainerConfigList([]);
        $list->append(new ContainerConfig(DummyContainer::class, ClassResolver::class, DummyAdapter::class));
        $list->append(new ContainerConfig(PimpleContainer::class, ClassResolver::class, PimpleAdapter::class));

        $detectors = new DetectorList(
            [InstalledClassDetector::class => ['supported' => [DummyContainer::class]]], $list
        );

        foreach ($detectors as $detector) {
            $this->assertInstanceOf(ContainerConfig::class, $detector->detect(null));
        }
    }

    public function testDetectsDetectNothing()
    {
        $list = new ContainerConfigList([]);
        $list->append(new ContainerConfig(DummyContainer::class, ClassResolver::class, DummyAdapter::class));
        $list->append(new ContainerConfig(PimpleContainer::class, ClassResolver::class, PimpleAdapter::class));

        $detectors = new DetectorList(
            [InstanceOfDetector::class => ['supported' => [DummyContainer::class, PimpleContainer::class]]], $list
        );

        foreach ($detectors as $detector) {
            $this->assertNull($detector->detect(null));
        }
    }
}
