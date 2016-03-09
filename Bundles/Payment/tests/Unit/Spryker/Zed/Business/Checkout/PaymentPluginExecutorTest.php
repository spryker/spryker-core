<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Payment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor;
use Spryker\Zed\Payment\Business\Exception\PaymentProviderNotFoundException;
use Spryker\Zed\Payment\PaymentDependencyProvider;

class PaymentPluginExecutorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testPreCheckShouldTriggerTestPaymentPlugin()
    {
        $preCheckPluginMock = $this->createPreCheckPluginMock();
        $preCheckPluginMock->expects($this->once())->method('checkCondition');

        $paymentPluginExecutor = $this->createPaymenPluginExecutor($preCheckPluginMock);
        $quoteTransfer = $this->createQuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $paymentPluginExecutor->executePreCheckPlugin($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testOrderSaverShouldTriggerTestPaymentPlugin()
    {
        $orderSavePluginMock = $this->createSavePluginMock();
        $orderSavePluginMock->expects($this->once())->method('saveOrder');

        $paymentPluginExecutor = $this->createPaymenPluginExecutor(null, $orderSavePluginMock);
        $quoteTransfer = $this->createQuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $paymentPluginExecutor->executeOrderSaverPlugin($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testPostCheckShouldTriggerTestPaymentPlugin()
    {
        $postCheckoutPluginMock = $this->createPostSavePluginMock();
        $postCheckoutPluginMock->expects($this->once())->method('executeHook');

        $paymentPluginExecutor = $this->createPaymenPluginExecutor(null, null, $postCheckoutPluginMock);
        $quoteTransfer = $this->createQuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $paymentPluginExecutor->executePostCheckPlugin($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testOrderSaverShouldThrowExceptionWhenNonExistantProviderUsed()
    {
        $this->setExpectedException(PaymentProviderNotFoundException::class);

        $paymentPluginExecutor = $this->createPaymenPluginExecutor(null, null, null);
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->getPayment()->setPaymentProvider('non existant provider');
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $paymentPluginExecutor->executeOrderSaverPlugin($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param null $preCheckPluginMock
     * @param null $orderSavePluginMock
     * @param null $postCheckPluginMock
     *
     * @return \Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor
     */
    protected function createPaymenPluginExecutor(
        $preCheckPluginMock = null,
        $orderSavePluginMock = null,
        $postCheckPluginMock = null
    ) {
        $paymentPluginExecutor = new PaymentPluginExecutor(
            $this->createCheckoutPlugins(
                $preCheckPluginMock,
                $orderSavePluginMock,
                $postCheckPluginMock
            )
        );

        return $paymentPluginExecutor;
    }

    /**
     * @param null $preCheckPluginMock
     * @param null $orderSavePluginMock
     * @param null $postCheckPluginMock
     *
     * @return array
     */
    protected function createCheckoutPlugins(
        $preCheckPluginMock = null,
        $orderSavePluginMock = null,
        $postCheckPluginMock = null
    ) {

        return [
            PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS => [
                'test' => $preCheckPluginMock,
            ],
            PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS => [
                'test' => $orderSavePluginMock,
            ],
            PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS => [
                'test' => $postCheckPluginMock,
            ],
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface
     */
    protected function createPreCheckPluginMock()
    {
        return $this->getMockBuilder(CheckoutPreConditionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface
     */
    protected function createSavePluginMock()
    {
        return $this->getMockBuilder(CheckoutSaveOrderInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface
     */
    protected function createPostSavePluginMock()
    {
        return $this->getMockBuilder(CheckoutPostSaveHookInterface::class)->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider('test');
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

}
