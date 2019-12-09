<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferValidity\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\ProductOfferValidity\Communication\Console\ProductOfferValidityConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferValidity
 * @group Communication
 * @group Console
 * @group ProductOfferValidityConsoleTest
 * Add your own group annotations below this line
 */
class ProductOfferValidityConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferValidity\ProductOfferValidityCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCommandIsExecutable(): void
    {
        $application = new Application();
        $application->add(new ProductOfferValidityConsole());

        $command = $application->find(ProductOfferValidityConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertSame(ProductOfferValidityConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
