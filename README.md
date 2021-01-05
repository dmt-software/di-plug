# DI-Plug

## Introduction
[psr-11](https://www.php-fig.org/psr/psr-11/) introduced a standardized way for packages, libraries and frameworks to
retrieve objects from containers. This package also allows adding dependencies to a container by a uniform interface
without the use of a specific container implementation. This will help developers to access the dependency injection
container to make installation of their packages more easy.

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
$container->set(SomeClass::class, function () {
    return new SomeClass($this->get(SomeDependency::class));
});
````

### Autodetect
```php
use DMT\DependencyInjection\ContainerFactory;
 
/** @var object|null $fromContainerInstanceOrNull */
$factory = new ContainerFactory();
$container = $factory->createContainer($fromContainerInstanceOrNull);
$container->set(SomeClass::class, function () {
    return new SomeClass($this->get(SomeDependency::class));
});
````

## Supports
 - [Aura dependency injection container](https://packagist.org/packages/aura/di)
 - [Illuminate container](https://packagist.org/packages/illuminate/container)
 - [League container](https://packagist.org/packages/league/container)
 - [PHP-DI container](https://packagist.org/packages/php-di/php-di)
 - [Pimple container](https://packagist.org/packages/pimple/pimple)