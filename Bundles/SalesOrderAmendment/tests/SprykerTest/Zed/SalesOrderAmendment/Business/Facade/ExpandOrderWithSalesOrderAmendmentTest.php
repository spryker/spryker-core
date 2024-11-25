<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group ExpandOrderWithSalesOrderAmendmentTest
 * Add your own group annotations below this line
 */
class ExpandOrderWithSalesOrderAmendmentTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([SalesOrderAmendmentBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldExpandOrderWithExistingSalesOrderAmendment(): void
    {
        // Arrange
        $salesOrderAmendment = $this->tester->createSalesOrderAmendment();
        $orderTransfer = (new OrderBuilder([
            OrderTransfer::ORDER_REFERENCE => $salesOrderAmendment->getOriginalOrderReferenceOrFail(),
        ]))->build();

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderWithSalesOrderAmendment($orderTransfer);

        // Assert
        $this->assertNotNull($orderTransfer->getSalesOrderAmendment());
        $this->assertSame($salesOrderAmendment->getUuidOrFail(), $orderTransfer->getSalesOrderAmendmentOrFail()->getUuid());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenSalesOrderAmendmentForOrderDoesNotExist(): void
    {
        // Arrange
        $this->tester->createSalesOrderAmendment();
        $orderTransfer = (new OrderBuilder([
            OrderTransfer::ORDER_REFERENCE => 'different-order-reference',
        ]))->build();

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderWithSalesOrderAmendment($orderTransfer);

        // Assert
        $this->assertNull($orderTransfer->getSalesOrderAmendment());
    }
}
