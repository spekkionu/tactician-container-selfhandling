<?php
namespace Spekkionu\Tactician\SelfExecuting\Test\Example;

use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

/**
 * Class ExampleBrokenCommand
 */
class ExampleBrokenCommand implements SelfExecutingCommand
{
    /**
     * @var string
     */
    public $message = 'self executing';

    /**
     * Has execute method instead of handle
     * @return string
     */
    public function execute()
    {
        return $this->message;
    }
}
