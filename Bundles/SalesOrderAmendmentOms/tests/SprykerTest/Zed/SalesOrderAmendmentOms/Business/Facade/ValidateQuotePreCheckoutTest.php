<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Oms\Communication\Plugin\Sales\ItemStateOrderItemExpanderPlugin;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Business
 * @group Facade
 * @group ValidateQuotePreCheckoutTest
 * Add your own group annotations below this line
 */
class ValidateQuotePreCheckoutTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester
     */
    protected SalesOrderAmendmentOmsBusinessTester $tester;

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_ORDER_AMENDMENT = 'order amendment';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAID = 'paid';

    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidator::GLOSSARY_KEY_VALIDATION_ORDER_NOT_BEING_AMENDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_BEING_AMENDED = 'sales_order_amendment_oms.validation.order_not_being_amended';

    /**
     * @uses \Spryker\Zed\Sales\SalesDependencyProvider::PLUGINS_ORDER_ITEM_EXPANDER
     *
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_EXPANDER = 'PLUGINS_ORDER_ITEM_EXPANDER';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureOrderAmendmentTestStateMachine();
        $this->tester->setDependency(static::PLUGINS_ORDER_ITEM_EXPANDER, [
            new ItemStateOrderItemExpanderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenOrderIsInCorrectState(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_ORDER_AMENDMENT);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $saveOrderTransfer->getOrderReferenceOrFail(),
        ]))->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->validateQuotePreCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenOrderReferenceIsMissing(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => null,
        ]))->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->validateQuotePreCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenOrderIsNotInOrderAmendmentState(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAID);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAID);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $saveOrderTransfer->getOrderReferenceOrFail(),
        ]))->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->validateQuotePreCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_BEING_AMENDED,
            $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessageOrFail(),
        );
    }
}
