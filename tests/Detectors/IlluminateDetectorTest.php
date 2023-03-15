<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DMT\DependencyInjection\Detectors\IlluminateDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use stdClass;

class IlluminateDetectorTest extends TestCase
{
    public function testDetectFromInstance()
    {
        $container = new Container();
        $detector = new IlluminateDetector();

        $this->assertInstanceOf(Resolver::class, $detector->detect($container));
    }

    public function testAutoDetect()
    {
        $detector = new IlluminateDetector();

        $this->assertInstanceOf(ClassResolver::class, $detector->detect(null));
    }

    public function testNothingDetected()
    {
        $detector = new IlluminateDetector();

        $this->assertNull($detector->detect(new stdClass()));
    }
}
