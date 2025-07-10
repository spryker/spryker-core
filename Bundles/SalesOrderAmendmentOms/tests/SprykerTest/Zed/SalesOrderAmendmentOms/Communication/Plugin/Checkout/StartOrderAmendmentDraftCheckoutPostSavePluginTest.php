<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Checkout\StartOrderAmendmentDraftCheckoutPostSavePlugin;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group StartOrderAmendmentDraftCheckoutPostSavePluginTest
 * Add your own group annotations below this line
 */
class StartOrderAmendmentDraftCheckoutPostSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_ORDER_AMENDMENT = 'order amendment';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_ORDER_AMENDMENT_DRAFT = 'order amendment draft';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

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
    public function testExecuteHookShouldTriggerOrderAmendmentStart(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);
        $quoteTransfer = (new QuoteTransfer())
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReferenceOrFail());

        // Act
        (new StartOrderAmendmentDraftCheckoutPostSavePlugin())->executeHook($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertSame(
            static::ORDER_ITEM_STATE_ORDER_AMENDMENT_DRAFT,
            $this->tester->getOrderItemCurrentState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail()),
        );
        $this->assertSame(
            static::ORDER_ITEM_STATE_ORDER_AMENDMENT_DRAFT,
            $this->tester->getOrderItemCurrentState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail()),
        );
    }

    /**
     * @return void
     */
    public function testExecuteHookShouldDoNothingWhenAmendmentOrderReferenceIsNotSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);
        $quoteTransfer = new QuoteTransfer();

        // Act
        (new StartOrderAmendmentDraftCheckoutPostSavePlugin())->executeHook($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertSame(
            static::ORDER_ITEM_STATE_ORDER_AMENDMENT,
            $this->tester->getOrderItemCurrentState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail()),
        );
        $this->assertSame(
            static::ORDER_ITEM_STATE_ORDER_AMENDMENT,
            $this->tester->getOrderItemCurrentState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail()),
        );
    }
}
