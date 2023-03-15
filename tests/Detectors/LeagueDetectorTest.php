<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DMT\DependencyInjection\Detectors\LeagueDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use stdClass;

class LeagueDetectorTest extends TestCase
{
    public function testDetectFromInstance()
    {
        $container = new Container();
        $detector = new LeagueDetector();

        $this->assertInstanceOf(Resolver::class, $detector->detect($container));

    }

    public function testAutoDetect()
    {
        $detector = new LeagueDetector();

        $this->assertInstanceOf(ClassResolver::class, $detector->detect(null));
    }

    public function testNothingDetected()
    {
        $detector = new LeagueDetector();

        $this->assertNull($detector->detect(new stdClass()));
    }
}
