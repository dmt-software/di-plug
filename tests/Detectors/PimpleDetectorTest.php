<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DMT\DependencyInjection\Detectors\PimpleDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\PropertyResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use stdClass;

class PimpleDetectorTest extends TestCase
{
    public function testDetectFromInstance()
    {
        $container = new Container();
        $detector = new PimpleDetector();

        $this->assertInstanceOf(Resolver::class, $detector->detect($container));
    }

    public function testCreateFromPsrContainerWrapper()
    {
        $container = new Psr11Container(new Container());
        $detector = new PimpleDetector();

        $this->assertInstanceOf(PropertyResolver::class, $detector->detect($container));

    }

    public function testAutoDetect()
    {
        $detector = new PimpleDetector();

        $this->assertInstanceOf(ClassResolver::class, $detector->detect(null));
    }

    public function testNothingDetected()
    {
        $detector = new PimpleDetector();

        $this->assertNull($detector->detect(new stdClass()));
    }
}
