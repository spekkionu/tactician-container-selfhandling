<?php
namespace Spekkionu\Tactician\SelfExecuting\Test\Example;

use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

/**
 * Class ExampleSelfExecutingCommandWithDependencies
 */
class ExampleSelfExecutingCommandWithDependencies implements SelfExecutingCommand
{
    /**
     * @param \DateTime $date
     * @return \DateTime
     */
    public function handle(\DateTime $date)
    {
        return $date;
    }
}
