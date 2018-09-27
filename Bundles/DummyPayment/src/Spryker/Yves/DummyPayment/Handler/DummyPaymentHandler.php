<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\DummyPayment\Exception\PaymentMethodNotFoundException;

class DummyPaymentHandler
{
    public const PAYMENT_PROVIDER = 'DummyPayment';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        DummyPaymentConfig::PAYMENT_METHOD_INVOICE => 'invoice',
        DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD => 'credit card',
    ];

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setDummyPayment($quoteTransfer, $paymentSelection);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPaymentProviderAndMethod(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $quoteTransfer->getPayment()
            ->setPaymentProvider(self::PAYMENT_PROVIDER)
            ->setPaymentMethod(self::$paymentMethods[$paymentSelection]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setDummyPayment(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $dummyPaymentTransfer = $this->getDummyPaymentTransfer($quoteTransfer, $paymentSelection);

        $quoteTransfer->getPayment()->setDummyPayment(clone $dummyPaymentTransfer);
    }

    /**
     * @return string
     */
    protected function getCurrency()
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @throws \Spryker\Yves\DummyPayment\Exception\PaymentMethodNotFoundException
     *
     * @return \Generated\Shared\Transfer\DummyPaymentTransfer
     */
    protected function getDummyPaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();
        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }
        $dummyPaymentTransfer = $quoteTransfer->getPayment()->$method();

        return $dummyPaymentTransfer;
    }
}
