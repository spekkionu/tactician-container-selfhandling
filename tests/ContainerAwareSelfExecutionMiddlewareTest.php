<?php
namespace Spekkionu\Tactician\SelfExecuting\Test;

use League\Container\Container;
use League\Tactician\CommandBus;
use Spekkionu\Tactician\SelfExecuting\ContainerAwareSelfExecutionMiddleware;
use Spekkionu\Tactician\SelfExecuting\SelfExecutionMiddleware;
use Spekkionu\Tactician\SelfExecuting\Test\Example\BasicCommand;
use Spekkionu\Tactician\SelfExecuting\Test\Example\ExampleBrokenCommand;
use Spekkionu\Tactician\SelfExecuting\Test\Example\ExampleSelfExecutingCommand;
use Spekkionu\Tactician\SelfExecuting\Test\Example\ExampleSelfExecutingCommandWithDependencies;

class ContainerAwareSelfExecutionMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SelfExecutionMiddleware
     */
    private $middleware;

    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * @var Container
     */
    private $container;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->container = new Container();
        $this->container->delegate(
            new \League\Container\ReflectionContainer
        );
        $this->middleware = new ContainerAwareSelfExecutionMiddleware($this->container);
        $this->bus = new CommandBus([
            $this->middleware
        ]);
    }

    public function test_self_executing_command()
    {
        $command = new ExampleSelfExecutingCommand();
        $result = $this->bus->handle($command);
        $this->assertEquals($command->message, $result);
    }

    public function test_self_executing_command_with_dependencies()
    {
        $date = new \DateTime();
        $this->container->add('DateTime', $date);
        $command = new ExampleSelfExecutingCommandWithDependencies();
        $result = $this->bus->handle($command);
        $this->assertSame($date, $result);
    }

    public function test_other_command()
    {
        $command = $this->getMockBuilder(BasicCommand::class)
            ->setMethods(['handle'])
            ->getMock();

        $command->expects($this->never())
            ->method('handle')
            ->willReturn('self executing');

        $result = $this->bus->handle($command);
        $this->assertNull($result);
    }

    /**
     * @expectedException \Spekkionu\Tactician\SelfExecuting\CommandHasNoHandleMethodException
     */
    public function test_command_without_handle_method()
    {
        $command = new ExampleBrokenCommand();
        $this->bus->handle($command);
    }
}
