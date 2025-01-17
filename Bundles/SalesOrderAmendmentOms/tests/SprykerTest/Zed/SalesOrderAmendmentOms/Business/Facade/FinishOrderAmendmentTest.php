<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsDependencyProvider;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Business
 * @group Facade
 * @group FinishOrderAmendmentTest
 * Add your own group annotations below this line
 */
class FinishOrderAmendmentTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester
     */
    protected SalesOrderAmendmentOmsBusinessTester $tester;

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_CANCELED = 'cancelled';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_ORDER_AMENDMENT = 'order amendment';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureOrderAmendmentTestStateMachine();
    }

    /**
     * @return void
     */
    public function testShouldFinishOrderAmendmentWhenAmendableOrderReferenceIsProvided(): void
    {
        // Arrange
        $amendableSaveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($amendableSaveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);
        $this->tester->setItemState($amendableSaveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $amendableSaveOrderTransfer->getOrderReferenceOrFail(),
        ]))->build();

        // Act
        $this->tester->getFacade()->finishOrderAmendment($quoteTransfer);

        // Assert
        $this->assertSame(
            static::ORDER_ITEM_STATE_CANCELED,
            $this->tester->getOrderItemCurrentState($amendableSaveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail()),
        );
        $this->assertSame(
            static::ORDER_ITEM_STATE_CANCELED,
            $this->tester->getOrderItemCurrentState($amendableSaveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail()),
        );
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenAmendableOrderReferenceIsNotProvided(): void
    {
        // Assert
        $this->tester->setDependency(
            SalesOrderAmendmentOmsDependencyProvider::FACADE_OMS,
            $this->tester->getSalesOrderAmendmentOmsOmsFacadeMock(),
        );

        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => null,
        ]))->build();

        // Act
        $this->tester->getFacade()->finishOrderAmendment($quoteTransfer);
    }
}
