<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker;

use Codeception\Actor;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerDebugConsole;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerWorkerConsole;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MessageBrokerCommunicationTester extends Actor
{
    use _generated\MessageBrokerCommunicationTesterActions;

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getWorkerConsoleCommandTester(): CommandTester
    {
        $this->mockWorker();
        $facade = $this->getFacade();

        $command = new MessageBrokerWorkerConsole();
        $command->setFacade($facade);

        return $this->getConsoleTester($command);
    }

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getDebugConsoleCommandTester(): CommandTester
    {
        $facade = $this->getFacade();

        $command = new MessageBrokerDebugConsole();
        $command->setFacade($facade);

        return $this->getConsoleTester($command);
    }
}
