<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\SalesInvoice\Communication\Console\OrderInvoiceSendConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesInvoice
 * @group Communication
 * @group Console
 * @group OrderInvoiceSendConsoleTest
 * Add your own group annotations below this line
 */
class OrderInvoiceSendConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesInvoice\SalesInvoiceCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function test(): void
    {
        $command = new OrderInvoiceSendConsole();
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->assertSame(OrderInvoiceSendConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
