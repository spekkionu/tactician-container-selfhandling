<?php
namespace Spekkionu\Tactician\SelfExecuting;

use League\Container\Container;
use League\Tactician\Middleware;

/**
 * Middleware are the plugins of Tactician. They receive each command that's
 * given to the CommandBus and can take any action they choose. Middleware can
 * continue the Command processing by passing the command they receive to the
 * $next callable, which is essentially the "next" Middleware in the chain.
 *
 * Depending on where they invoke the $next callable, Middleware can execute
 * their custom logic before or after the Command is handled. They can also
 * modify, log, or replace the command they receive. The sky's the limit.
 */
class ContainerAwareSelfExecutionMiddleware implements Middleware
{
    /**
     * @var Container
     */
    private $container;

    /**
     * SelfExecutionMiddleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     * @throws CommandHasNoHandleMethodException
     */
    public function execute($command, callable $next)
    {
        if (!$command instanceof SelfExecutingCommand) {
            return $next($command);
        }
        if (!method_exists($command, 'handle')) {
            throw new CommandHasNoHandleMethodException('Command does not have handle method');
        }
        return $this->container->call([$command, 'handle']);
    }
}
