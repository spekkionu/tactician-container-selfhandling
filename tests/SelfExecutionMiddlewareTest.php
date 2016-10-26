<?php
namespace Spekkionu\Tactician\SelfExecuting\Test;

use League\Tactician\CommandBus;
use Spekkionu\Tactician\SelfExecuting\SelfExecutionMiddleware;
use Spekkionu\Tactician\SelfExecuting\Test\Example\BasicCommand;
use Spekkionu\Tactician\SelfExecuting\Test\Example\ExampleBrokenCommand;
use Spekkionu\Tactician\SelfExecuting\Test\Example\ExampleSelfExecutingCommand;

class SelfExecutionMiddlewareTest extends \PHPUnit_Framework_TestCase
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
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->middleware = new SelfExecutionMiddleware();
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
