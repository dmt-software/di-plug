<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Config\ContainerConfigList;
use DMT\DependencyInjection\Detectors\InstalledClassDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\Test\DependencyInjection\Fixtures\DummyAdapter;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use PHPUnit\Framework\TestCase;
use Pimple\Container as PimpleContainer;

class InstalledClassDetectorTest extends TestCase
{
    public function testDetect()
    {
        $config = new ContainerConfig(DummyContainer::class, ClassResolver::class, DummyAdapter::class);
        $pimple = new ContainerConfig(PimpleContainer::class, ClassResolver::class, PimpleAdapter::class);

        $list = new ContainerConfigList([]);
        $list->append($config);
        $list->append($pimple);

        $detector = new InstalledClassDetector($list);
        $this->assertEquals($config, $detector->detect(null));
    }

    public function testDetectNothing()
    {
        $list = new ContainerConfigList([]);
        $list->append(new ContainerConfig('__missing_class__', ClassResolver::class, DummyAdapter::class));

        $detector = new InstalledClassDetector($list);
        $this->assertNull($detector->detect(null));
    }
}
