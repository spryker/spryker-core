<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsMultiThread\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OmsMultiThread
 * @group Business
 * @group Facade
 * @group Facade
 * @group OmsMultiThreadFacadeTest
 * Add your own group annotations below this line
 */
class OmsMultiThreadFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OmsMultiThread\OmsMultiThreadBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCanExpandSalesOrderEntityTransferWithOmsProcessorId(): void
    {
        // Arrange
        $omsMultiThreadFacade = $this->tester->getFacade();

        // Act
        $salesOrderEntityTransfer = $omsMultiThreadFacade->expandSalesOrderEntityTransferWithOmsProcessorIdentifier(
            new SpySalesOrderEntityTransfer(),
            new QuoteTransfer()
        );
        $omsProcessorId = $salesOrderEntityTransfer->getOmsProcessorIdentifier();

        // Assert
        $this->assertIsInt($omsProcessorId);
        $this->assertGreaterThanOrEqual(1, $omsProcessorId);
        $this->assertLessThanOrEqual(10, $omsProcessorId);
    }
}
