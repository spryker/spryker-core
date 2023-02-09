<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel;

use Codeception\Actor;
use Spryker\Zed\Propel\Communication\Console\MigrationCheckConsole;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class PropelCommunicationTester extends Actor
{
    use _generated\PropelCommunicationTesterActions;

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getMigrationCheckConsoleCommandTester(): CommandTester
    {
        $factory = $this->getFactory();
        $command = (new MigrationCheckConsole())->setFactory($factory);

        return $this->getConsoleTester($command);
    }
}
