<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SalesOrderAmendment\Client;

use Codeception\Test\Unit;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface;
use Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use SprykerTest\Client\SalesOrderAmendment\SalesOrderAmendmentClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SalesOrderAmendment
 * @group Client
 * @group CancelOrderAmendmentTest
 * Add your own group annotations below this line
 */
class CancelOrderAmendmentTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SalesOrderAmendment\SalesOrderAmendmentClientTester
     */
    protected SalesOrderAmendmentClientTester $tester;

    /**
     * @return void
     */
    public function testCallsQuoteClientClearQuote(): void
    {
        // Assert
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::CLIENT_QUOTE,
            $this->getQuoteClientMock(),
        );

        // Act
        $this->tester->getClient()->cancelOrderAmendment();
    }

    /**
     * @return \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface
     */
    protected function getQuoteClientMock(): SalesOrderAmendmentToQuoteClientInterface
    {
        $salesOrderAmendmentToQuoteClient = $this->getMockBuilder(SalesOrderAmendmentToQuoteClientInterface::class)
            ->getMock();
        $salesOrderAmendmentToQuoteClient->expects($this->once())->method('clearQuote');

        return $salesOrderAmendmentToQuoteClient;
    }
}
