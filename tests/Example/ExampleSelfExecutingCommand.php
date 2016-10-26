<?php
namespace Spekkionu\Tactician\SelfExecuting\Test\Example;

use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

/**
 * Class ExampleSelfExecutingCommand
 */
class ExampleSelfExecutingCommand implements SelfExecutingCommand
{
    /**
     * @var string
     */
    public $message = 'self executing';

    /**
     * @return string
     */
    public function handle()
    {
        return $this->message;
    }
}
