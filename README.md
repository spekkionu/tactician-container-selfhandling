# Tactician Container-Aware Self-Handling Commands

[![Latest Stable Version](https://poser.pugx.org/spekkionu/tactician-container-selfhandling/v/stable)](https://packagist.org/packages/spekkionu/tactician-container-selfhandling)
[![Build Status](https://travis-ci.org/spekkionu/tactician-container-selfhandling.svg?branch=master)](https://travis-ci.org/spekkionu/tactician-container-selfhandling)
[![License](https://poser.pugx.org/spekkionu/tactician-container-selfhandling/license)](https://github.com/spekkionu/tactician-container-selfhandling/blob/master/LICENSE.md)
[![Code Coverage](https://scrutinizer-ci.com/g/spekkionu/tactician-container-selfhandling/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/spekkionu/tactician-container-selfhandling/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spekkionu/tactician-container-selfhandling/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spekkionu/tactician-container-selfhandling/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6e3dae70-b026-452e-92fd-d0d27b065aac/mini.png)](https://insight.sensiolabs.com/projects/6e3dae70-b026-452e-92fd-d0d27b065aac)

## Install

Via Composer

``` bash
$ composer require spekkionu/tactician-container-selfhandling
```

If you want to use the container aware commands you will also need to install `league/container`

## Usage

### Add MiddleWare

``` php
use League\Tactician\CommandBus;
use Spekkionu\Tactician\SelfExecuting\SelfExecutionMiddleware;

$commandBus = new CommandBus([
    // any other pre-execution middleware
    new SelfExecutionMiddleware(),
    // other middleware to handle non-self-executing commands
    // any other post-execution middleware
]);
```

### Create a Command

Your commands must implement `Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand` and have a handle() method.

The handle method must have no parameters.

``` php
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

/**
 * Class ExampleSelfExecutingCommand
 */
class ExampleSelfExecutingCommand implements SelfExecutingCommand
{
    /**
     * @return string
     */
    public function handle()
    {
        // do work here
    }
}
```

### Run Command

``` php
$commandBus->handle(new ExampleSelfExecutingCommand());
```

## Container-Aware Self-Executing Commands

Container aware commands will have dependencies injected into the `handle()` method from `league/container`.

``` php
use League\Container\Container;
use League\Tactician\CommandBus;
use Spekkionu\Tactician\SelfExecuting\SelfExecutionMiddleware;

// Setup the Container
$container = new Container();
$container->delegate(
    new \League\Container\ReflectionContainer
);
$container->add('Logger', function() {
    return new Logger();
});

// Setup the command bus
$commandBus = new CommandBus([
    // any other pre-execution middleware
    new ContainerAwareSelfExecutionMiddleware($container),
    // other middleware to handle non-self-executing commands
    // any other post-execution middleware
]);
```

``` php
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

/**
 * Class ExampleSelfExecutingCommand
 */
class ExampleSelfExecutingCommand implements SelfExecutingCommand
{
    /**
     * The logger will be injected automatically
     * @return string
     */
    public function handle(Logger $logger)
    {
        $logger->log('log message');
    }
}
```

``` php
$commandBus->handle(new ExampleSelfExecutingCommand());
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
