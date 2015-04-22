<?php

namespace SprykerFeature\Zed\Console\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

use Symfony\Component\Console\Command\Command;

class ConsoleSettings
{

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [];
    }
}
