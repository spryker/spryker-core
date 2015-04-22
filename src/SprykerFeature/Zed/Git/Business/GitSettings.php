<?php

namespace SprykerFeature\Zed\Git\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Console\Command\Command;

class GitSettings
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
        return [
            $this->locator->git()->consoleAddConsole(),
            $this->locator->git()->consoleCheckoutConsole(),
            $this->locator->git()->consoleCleanConsole(),
            $this->locator->git()->consoleCommitConsole(),
            $this->locator->git()->consoleFetchConsole(),
            $this->locator->git()->consoleMergeConsole(),
            $this->locator->git()->consolePrepareTagConsole(),
            $this->locator->git()->consolePullConsole(),
            $this->locator->git()->consolePullNPushConsole(),
            $this->locator->git()->consolePushConsole(),
            $this->locator->git()->consoleResetConsole(),
            $this->locator->git()->consoleStatusConsole(),
        ];
    }
}
