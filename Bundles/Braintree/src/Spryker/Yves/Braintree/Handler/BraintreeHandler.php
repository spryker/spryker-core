<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Braintree\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Braintree\BraintreeClientInterface;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Yves\Currency\Plugin\CurrencyPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class BraintreeHandler
{
    const PAYMENT_PROVIDER = 'braintree';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        BraintreeConstants::PAYMENT_METHOD_PAY_PAL => 'pay_pal',
        BraintreeConstants::PAYMENT_METHOD_CREDIT_CARD => 'credit_card',
    ];

    /**
     * @var \Spryker\Client\Braintree\BraintreeClientInterface
     */
    protected $braintreeClient;

    /**
     * @var \Spryker\Yves\Currency\Plugin\CurrencyPluginInterface
     */
    protected $currencyPlugin;

    /**
     * @param \Spryker\Client\Braintree\BraintreeClientInterface $braintreeClient
     * @param \Spryker\Yves\Currency\Plugin\CurrencyPluginInterface $currencyPlugin
     */
    public function __construct(BraintreeClientInterface $braintreeClient, CurrencyPluginInterface $currencyPlugin)
    {
        $this->braintreeClient = $braintreeClient;
        $this->currencyPlugin = $currencyPlugin;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setBraintreePayment($request, $quoteTransfer, $paymentSelection);

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
            ->setPaymentProvider(BraintreeConstants::PROVIDER_NAME)
            ->setPaymentMethod(static::$paymentMethods[$paymentSelection]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setBraintreePayment(Request $request, QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $braintreePaymentTransfer = $this->getBraintreePaymentTransfer($quoteTransfer, $paymentSelection);
        $nonce = $request->request->get('payment_method_nonce');
        if ($nonce === null) {
            return;
        }

        $billingAddress = $quoteTransfer->getBillingAddress();
        $braintreePaymentTransfer
            ->setAccountBrand(static::$paymentMethods[$paymentSelection])
            ->setBillingAddress($billingAddress)
            ->setShippingAddress($quoteTransfer->getShippingAddress())
            ->setEmail($quoteTransfer->getCustomer()->getEmail())
            ->setCurrencyIso3Code($this->getCurrency())
            ->setLanguageIso2Code($billingAddress->getIso2Code())
            ->setClientIp($request->getClientIp())
            ->setNonce($nonce);

        $quoteTransfer->getPayment()->setBraintree(clone $braintreePaymentTransfer);
    }

    /**
     * @return string
     */
    protected function getCurrency()
    {
        return $this->currencyPlugin->getCurrent()->getCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\BraintreePaymentTransfer
     */
    protected function getBraintreePaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $method = 'get' . ucfirst($paymentSelection);
        $braintreePaymentTransfer = $quoteTransfer->getPayment()->$method();

        return $braintreePaymentTransfer;
    }
}
