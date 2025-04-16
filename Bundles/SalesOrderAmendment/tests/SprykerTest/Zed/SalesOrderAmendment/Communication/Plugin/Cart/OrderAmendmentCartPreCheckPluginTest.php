<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Cart\OrderAmendmentCartPreCheckPlugin;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OrderAmendmentCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentCartPreCheckPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Checker\CartChecker::GLOSSARY_KEY_CART_CANT_BE_AMENDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CART_CANT_BE_AMENDED = 'sales_order_amendment.validation.cart.cart_cant_be_amended';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([SalesOrderAmendmentCommunicationTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldCheckOrderAmendmentInCart(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->haveOrderFromQuote(
            $this->tester->createQuoteTransfer($customerTransfer),
            SalesOrderAmendmentCommunicationTester::DEFAULT_OMS_PROCESS_NAME,
        );
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $orderTransfer->getOrderReferenceOrFail(),
        ]))->build();

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentCartPreCheckPlugin())
            ->check((new CartChangeTransfer())->setQuote($quoteTransfer));

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testShouldNotCheckOrderAmendmentInCart(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->haveOrderFromQuote(
            $this->tester->createQuoteTransfer($customerTransfer),
            SalesOrderAmendmentCommunicationTester::DEFAULT_OMS_PROCESS_NAME,
        );
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $orderTransfer->getOrderReferenceOrFail(),
        ]))->build();

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentCartPreCheckPlugin())
            ->check((new CartChangeTransfer())->setQuote($quoteTransfer));

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_CART_CANT_BE_AMENDED,
            $cartPreCheckResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipCheckWhenAmendmentOrderReferenceNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => null,
        ]))->build();

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentCartPreCheckPlugin())
            ->check((new CartChangeTransfer())->setQuote($quoteTransfer));

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessResponseWhenCustomerReferencesMatch(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference',
            QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference('customer-reference'),
            QuoteTransfer::CUSTOMER_REFERENCE => 'customer-reference',
        ]))->build();

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentCartPreCheckPlugin())
            ->check((new CartChangeTransfer())->setQuote($quoteTransfer));

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenQuoteNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartChangeTransfer` is null.');

        // Act
        (new OrderAmendmentCartPreCheckPlugin())->check((new CartChangeTransfer())->setQuote(null));
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenCustomerNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::CUSTOMER => null,
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'fake-order-reference',
        ]))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customer" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        (new OrderAmendmentCartPreCheckPlugin())->check((new CartChangeTransfer())->setQuote($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenCustomerReferenceNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::CUSTOMER => new CustomerTransfer(),
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'fake-order-reference',
        ]))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customerReference" of transfer `Generated\Shared\Transfer\CustomerTransfer` is null.');

        // Act
        (new OrderAmendmentCartPreCheckPlugin())->check((new CartChangeTransfer())->setQuote($quoteTransfer));
    }
}
