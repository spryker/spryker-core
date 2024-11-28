<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentApp\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface;
use Spryker\Zed\PaymentApp\PaymentAppDependencyProvider;
use Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface;
use SprykerTest\Zed\PaymentApp\PaymentAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentAppFacadeTest
 * Add your own group annotations below this line
 */
class PaymentAppFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentApp\PaymentAppBusinessTester
     */
    protected PaymentAppBusinessTester $tester;

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestReturnsSuccessfulResponseIfNoPluginsWereExecuted(): void
    {
        // Arrange
        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([], [
            QuoteTransfer::PAYMENT => (new PaymentTransfer()),
            QuoteTransfer::PAYMENTS => [(new PaymentTransfer())],
            QuoteTransfer::STORE => (new StoreTransfer()),
        ]);
        $updatedPaymentTransfer = (new PaymentTransfer());

        $this->setBusinessDependencies($updatedPaymentTransfer, []);

        // Act
        $expressCheckoutPaymentResponseTransfer = $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);

        // Assert
        $this->assertCount(0, $expressCheckoutPaymentResponseTransfer->getErrors());
        $this->assertSame(
            $expressCheckoutPaymentRequestTransfer->getQuote(),
            $expressCheckoutPaymentResponseTransfer->getQuote(),
        );
        $this->assertSame($updatedPaymentTransfer, $expressCheckoutPaymentResponseTransfer->getQuote()->getPayment());
        $this->assertSame($updatedPaymentTransfer, $expressCheckoutPaymentResponseTransfer->getQuote()->getPayments()[0]);
    }

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestReturnsSuccessfulResponseIfAllPluginsWereExecutedSuccessfully(): void
    {
        // Arrange
        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([], [
            QuoteTransfer::PAYMENT => (new PaymentTransfer()),
            QuoteTransfer::PAYMENTS => [(new PaymentTransfer())],
            QuoteTransfer::STORE => (new StoreTransfer()),
        ]);
        $updatedPaymentTransfer = (new PaymentTransfer());

        $expressCheckoutPaymentRequestProcessorPluginMock = $this->createMock(ExpressCheckoutPaymentRequestProcessorPluginInterface::class);
        $expressCheckoutPaymentRequestProcessorPluginMock->expects($this->once())->method('processExpressCheckoutPaymentRequest')
            ->willReturnCallback(function (ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer) {
                return (new ExpressCheckoutPaymentResponseTransfer())
                    ->setQuote($expressCheckoutPaymentRequestTransfer->getQuote());
            });
        $this->setBusinessDependencies($updatedPaymentTransfer, [$expressCheckoutPaymentRequestProcessorPluginMock]);

        // Act
        $expressCheckoutPaymentResponseTransfer = $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);

        // Assert
        $this->assertCount(0, $expressCheckoutPaymentResponseTransfer->getErrors());
        $this->assertNotNull($expressCheckoutPaymentResponseTransfer->getQuote()->getStore());
        $this->assertSame($updatedPaymentTransfer, $expressCheckoutPaymentResponseTransfer->getQuote()->getPayment());
        $this->assertSame($updatedPaymentTransfer, $expressCheckoutPaymentResponseTransfer->getQuote()->getPayments()[0]);
    }

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestReturnsFailedResponseIfAtLestOnePluginHasFailed(): void
    {
        // Arrange
        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([], [
            QuoteTransfer::PAYMENT => (new PaymentTransfer()),
            QuoteTransfer::PAYMENTS => [(new PaymentTransfer())],
            QuoteTransfer::STORE => (new StoreTransfer()),
        ]);
        $updatedPaymentTransfer = (new PaymentTransfer());
        $errorTransfer = (new ErrorTransfer());

        $expressCheckoutPaymentRequestProcessorPluginMock = $this->createMock(ExpressCheckoutPaymentRequestProcessorPluginInterface::class);
        $expressCheckoutPaymentRequestProcessorPluginMock->method('processExpressCheckoutPaymentRequest')
            ->willReturnCallback(function (ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer) use ($errorTransfer) {
                return (new ExpressCheckoutPaymentResponseTransfer())
                    ->addError($errorTransfer)
                    ->setQuote($expressCheckoutPaymentRequestTransfer->getQuote());
            });
        $this->setBusinessDependencies($updatedPaymentTransfer, [$expressCheckoutPaymentRequestProcessorPluginMock]);

        // Act
        $expressCheckoutPaymentResponseTransfer = $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);

        // Assert
        $this->assertCount(1, $expressCheckoutPaymentResponseTransfer->getErrors());
        $this->assertSame($errorTransfer, $expressCheckoutPaymentResponseTransfer->getErrors()[0]);
        $this->assertNotNull($expressCheckoutPaymentResponseTransfer->getQuote()->getStore());
        $this->assertSame($updatedPaymentTransfer, $expressCheckoutPaymentResponseTransfer->getQuote()->getPayment());
        $this->assertSame($updatedPaymentTransfer, $expressCheckoutPaymentResponseTransfer->getQuote()->getPayments()[0]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param list<\Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface> $expressCheckoutPaymentRequestProcessorPlugins
     *
     * @return void
     */
    protected function setBusinessDependencies(
        PaymentTransfer $paymentTransfer,
        array $expressCheckoutPaymentRequestProcessorPlugins
    ): void {
        $paymentFacadeMock = $this->createMock(PaymentAppToPaymentFacadeInterface::class);
        $paymentFacadeMock->expects($this->once())->method('expandPaymentWithPaymentSelection')
            ->willReturn($paymentTransfer);
        $this->tester->setDependency(
            PaymentAppDependencyProvider::FACADE_PAYMENT,
            $paymentFacadeMock,
        );
        $this->tester->setDependency(
            PaymentAppDependencyProvider::PLUGINS_EXPRESS_CHECKOUT_PAYMENT_REQUEST_PROCESSOR,
            $expressCheckoutPaymentRequestProcessorPlugins,
        );

        $cartFacadeMock = $this->createMock(PaymentAppToCartFacadeInterface::class);
        $cartFacadeMock->expects($this->atMost(1))->method('reloadItemsInQuote')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return (new QuoteResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setQuoteTransfer($quoteTransfer);
            });

        $this->tester->setDependency(
            PaymentAppDependencyProvider::FACADE_CART,
            $cartFacadeMock,
        );
    }
}
