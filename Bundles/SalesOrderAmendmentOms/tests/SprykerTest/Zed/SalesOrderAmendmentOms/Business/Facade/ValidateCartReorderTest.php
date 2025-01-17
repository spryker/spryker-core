<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Business
 * @group Facade
 * @group ValidateCartReorderTest
 * Add your own group annotations below this line
 */
class ValidateCartReorderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester
     */
    protected SalesOrderAmendmentOmsBusinessTester $tester;

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAYMENT_PENDING = 'payment pending';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAID = 'paid';

    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidator::GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE = 'sales_order_amendment_oms.validation.order_not_amendable';

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
    public function testShouldReturnEmptyErrorCollectionWhenProvidedOrderIsAmendable(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $saveOrderTransfer->getOrderReferenceOrFail(),
        ]))->build();

        $cartReorderTransfer = (new CartReorderTransfer())->setQuote($quoteTransfer);
        $cartReorderResponseTransfer = new CartReorderResponseTransfer();

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->validateCartReorder(
            $cartReorderTransfer,
            $cartReorderResponseTransfer,
        );

        // Assert
        $this->assertCount(0, $cartReorderResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenProvidedOrderIsNotAmendable(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAID);
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $saveOrderTransfer->getOrderReferenceOrFail(),
        ]))->build();

        $cartReorderTransfer = (new CartReorderTransfer())->setQuote($quoteTransfer);
        $cartReorderResponseTransfer = new CartReorderResponseTransfer();

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->validateCartReorder(
            $cartReorderTransfer,
            $cartReorderResponseTransfer,
        );

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE,
            $cartReorderResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderTransfer = new CartReorderTransfer();
        $cartReorderResponseTransfer = new CartReorderResponseTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        $this->tester->getFacade()->validateCartReorder(
            $cartReorderTransfer,
            $cartReorderResponseTransfer,
        );
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::AMENDMENT_ORDER_REFERENCE => null]))->build();
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote($quoteTransfer);
        $cartReorderResponseTransfer = new CartReorderResponseTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "amendmentOrderReference" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        $this->tester->getFacade()->validateCartReorder(
            $cartReorderTransfer,
            $cartReorderResponseTransfer,
        );
    }
}
