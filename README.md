# DI-Plug

## Introduction
[psr-11](https://www.php-fig.org/psr/psr-11/) introduced a standardized way for packages, libraries and frameworks to
retrieve objects from containers. This package also allows adding dependencies to a container by a uniform interface
without the use of a specific container implementation. This will help developers to access the dependency injection
container to make installation of their packages easier.

## Installation
```bash
composer require dmt-software/di-plug
```

## Usage

### Manual create the container
```php
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Container;
use Pimple\Container as PimpleContainer;
 
$container = new Container(new PimpleAdapter(new PimpleContainer()));
$container->set(SomeClass::class, fn() => new SomeClass($container->get(SomeDependency::class)));
````

### Autodetect

Use the container builder to wrap your container instance within the `DMT\DependencyInjection\Container`.   

```php
use DMT\DependencyInjection\ContainerFactory;
 
/** @var object $supportedContainerInstance */
$factory = new ContainerFactory();
$container = $factory->createContainer($supportedContainerInstance);
$container->set(SomeClass::class, fn() => new SomeClass($container->get(SomeDependency::class)));
````

### Autodiscovery

> NOTE: When another dependency uses a supported container, autodiscovery might end up using a different container than
> expected.
 
The code can discover the dependency container installed.

```php
use DMT\DependencyInjection\ContainerFactory;
 
$factory = new ContainerFactory();
$container = $factory->createContainer();
$container->set(SomeClass::class, fn() => new SomeClass($container->get(SomeDependency::class)));
````

## Concepts

### Instances without registration

If the `Container::get()` is called with a className that is not present in the container will create a new instance for
that class. This also counts for calls with made with constructor arguments.

| call                            | set    | returns         |
|---------------------------------|--------|-----------------|
| Container::get(Some::class)     | false  | new instance    |
| Container::get(Some::class)     | true   | from dependency |
| Container::get(Some::class, 12) | true   | new instance    |

> NOTE: when the container creates a new instance it will NOT be set in the container.
> Not even when it is called with the same constructor arguments.

### Auto Inject Container

Instead of injection all the dependencies, one can choose to inject just the container and resolve the dependencies when
needed. This can easily be achieved by using the `HasContainer` trait.   

```php
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\Traits\HasContainer;

class SomeClass
{
    use HasContainer;
    
    public function doSomething(): void
    {
        $dependency = $this->getContainer()->get(SomeDependency::class);
        $dependency->doSomething();
    }
}
```

This even works when the `HasContainer` is used within another trait, but just one level deep.
Using the trait below f.i. enables `getTwigEngine()` call to retrieve the template engine in the parent class.

```php
use DMT\DependencyInjection\Traits\HasContainer;
use Twig\Environment;

trait HasTwigEngine
{
    use HasContainer;
    
    public function getTwigEngine(): Environment
    {
        return $this->getContainer()->get(Environment::class);
    }
}
```

### ServiceProvider

A service provider can be used to register or change dependencies in the container.  

```php
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\ServiceProviderInterface;
use Twig\Environment;
use Twig\Extension\StringLoaderExtension;

class MyServiceProvider implements ServiceProviderInterface
{
    /**
     * Register dependencies and ensure twig is enabled with string loader extension.   
     */
    public function register(Container $container): void
    {
        if (!$container->has(Environment::class)) {
            $container->set(Environment::class, fn() => new Environment());
        }
        
        $env = $container->get(Environment::class);
        if (!$env->hasExtention(StringLoaderExtension::class)) {
            $env->addExtension(new StringLoaderExtension());
        }

        $container->set(MyClassInterface::class, fn() => new MyClass($container->get(Environment::class)));
    }
}
```

## Supports
 - [Aura dependency injection container](https://packagist.org/packages/aura/di)
 - [Illuminate container](https://packagist.org/packages/illuminate/container)
 - [League container](https://packagist.org/packages/league/container)
 - [PHP-DI container](https://packagist.org/packages/php-di/php-di)
 - [Pimple container](https://packagist.org/packages/pimple/pimple)
