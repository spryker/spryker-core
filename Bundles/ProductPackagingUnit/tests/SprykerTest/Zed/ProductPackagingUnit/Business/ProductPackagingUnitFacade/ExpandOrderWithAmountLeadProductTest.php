<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandOrderWithAmountLeadProductTest
 * Add your own group annotations below this line
 */
class ExpandOrderWithAmountLeadProductTest extends Unit
{
    /**
     * @var int
     */
    protected const AMOUNT_VALUE = 5;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandOrderWithAmountLeadProduct(): void
    {
        // Arrange
        $testStateMachineProcessName = 'Test01';

        $this->tester->configureTestStateMachine([$testStateMachineProcessName]);

        $productTransfer = $this->tester->haveProduct();

        $savedOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
            'amountSku' => $productTransfer->getSku(),
            'amount' => static::AMOUNT_VALUE,
        ], $testStateMachineProcessName);

        $orderTransfer = (new OrderTransfer())->fromArray($savedOrderTransfer->toArray(), true);

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderWithAmountLeadProduct($orderTransfer);

        // Assert
        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
    }
}
