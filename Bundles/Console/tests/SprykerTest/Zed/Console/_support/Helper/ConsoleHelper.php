<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Console\Helper;

use Codeception\Module;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ConsoleHelper extends Module
{
    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getConsoleTester(Command $command): CommandTester
    {
        $application = new Application();
        $application->add($command);

        $command = $application->find($command->getName());

        return new CommandTester($command);
    }
}
