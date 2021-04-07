<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group CheckConfirmedOrderTest
 */
class CheckConfirmedOrderTest extends Test
{
    protected const CURRENCY_ISO_CODE = 'CODE';
    protected const ORDER_REFERENCE = 'ORDER_REFERENCE';
    protected const CUSTOMER_REFERENCE = 'CUSTOMER_REFERENCE';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testCheckConfirmedOrderWithCreatedSuccessfulOrder(): void
    {
        // Arrange
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();
        $quoteTransfer = $this->createCustomerOrderByQuoteTransfer();
        $quoteTransfer->setOrderConfirmed(true);

        // Act
        $confirmedOrderCheck = $this->tester->getFacade()->checkConfirmedOrder($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($confirmedOrderCheck);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotNull($quoteTransfer->getOrderReference());
        $this->assertEquals(
            $quoteTransfer->getOrderReference(),
            $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
        );
    }

    /**
     * @return void
     */
    public function testCheckConfirmedOrderWithCreatedFailedOrder(): void
    {
        // Arrange
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();
        $quoteTransfer = $this->createCustomerOrderByQuoteTransfer();
        $quoteTransfer->setOrderConfirmed(false);

        // Act
        $confirmedOrderCheck = $this->tester->getFacade()->checkConfirmedOrder($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($confirmedOrderCheck);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotNull($quoteTransfer->getOrderReference());
        $this->assertEquals(
            $quoteTransfer->getOrderReference(),
            $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
        );
    }

    /**
     * @return void
     */
    public function testCheckConfirmedOrderWithNotCreatedOrder(): void
    {
        // Arrange
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();
        $quoteTransfer = $this->createQuoteTransferWithoutOrder();
        $quoteTransfer->setOrderConfirmed(false);

        // Act
        $confirmedOrderCheck = $this->tester->getFacade()->checkConfirmedOrder($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($confirmedOrderCheck);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotNull($quoteTransfer->getOrderReference());
        $this->assertNotEquals(
            $quoteTransfer->getOrderReference(),
            $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createCustomerOrderByQuoteTransfer(): QuoteTransfer
    {
        $currencyTransfer = (new CurrencyBuilder([CurrencyTransfer::CODE => static::CURRENCY_ISO_CODE]))->build();
        $customerTransfer = (new CustomerBuilder([CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE]))->build();
        $quoteTransfer = $this->tester->buildQuote([
            QuoteTransfer::CURRENCY => $currencyTransfer,
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]);
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setOrderReference($saveOrderTransfer->getOrderReference());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithoutOrder(): QuoteTransfer
    {
        $currencyTransfer = (new CurrencyBuilder([CurrencyTransfer::CODE => static::CURRENCY_ISO_CODE]))->build();
        $customerTransfer = (new CustomerBuilder([CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE]))->build();
        $quoteTransfer = $this->tester->buildQuote([
            QuoteTransfer::CURRENCY => $currencyTransfer,
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            QuoteTransfer::ORDER_REFERENCE => static::ORDER_REFERENCE
        ]);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return (new CheckoutResponseTransfer())
            ->setSaveOrder(new SaveOrderTransfer())
            ->setIsSuccess(true);
    }
}
