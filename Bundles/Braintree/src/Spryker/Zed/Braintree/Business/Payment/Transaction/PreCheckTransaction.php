<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction;

use Braintree\PaymentInstrumentType;
use Braintree\Transaction as BraintreeTransaction;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyInterface;

class PreCheckTransaction extends AbstractTransaction
{
    /**
     * @var \Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Braintree\BraintreeConfig $brainTreeConfig
     * @param \Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyInterface $moneyFacade
     */
    public function __construct(BraintreeConfig $brainTreeConfig, BraintreeToMoneyInterface $moneyFacade)
    {
        parent::__construct($brainTreeConfig);

        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return string
     */
    protected function getTransactionType()
    {
        return ApiConstants::SALE;
    }

    /**
     * PreCheck has no transaction code defined by braintree, added for logging purposes.
     *
     * @return string
     */
    protected function getTransactionCode()
    {
        return 'pre check';
    }

    /**
     * @return \Braintree\Result\Error|\Braintree\Result\Successful|\Braintree\Transaction
     */
    protected function doTransaction()
    {
        $requestData = $this->getRequestData();

        return $this->preCheck($requestData);
    }

    /**
     * @param array $requestData
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful|\Braintree\Transaction
     */
    protected function preCheck(array $requestData)
    {
        return BraintreeTransaction::sale($requestData);
    }

    /**
     * @return array
     */
    protected function getRequestData()
    {
        return [
            'amount' => $this->getAmount(),
            'paymentMethodNonce' => $this->getNonce(),
            'options' => $this->getRequestOptions(),
            'customer' => $this->getCustomerData(),
            'billing' => $this->getCustomerAddressData($this->getBillingAddress()),
            'shipping' => $this->getCustomerAddressData($this->getShippingAddress()),
            'channel' => $this->config->getChannel(),
        ];
    }

    /**
     * @return array
     */
    protected function getRequestOptions()
    {
        return [
            'threeDSecure' => [
                'required' => $this->config->getIs3DSecure(),
            ],
            'storeInVault' => $this->config->getIsVaulted(),
        ];
    }

    /**
     * @return array
     */
    protected function getCustomerData()
    {
        $customerTransfer = $this->getCustomer();
        $billingAddressTransfer = $this->getBillingAddress();

        return [
            'firstName' => $customerTransfer->getFirstName(),
            'lastName' => $customerTransfer->getLastName(),
            'email' => $customerTransfer->getEmail(),
            'company' => $billingAddressTransfer->getCompany(),
            'phone' => $billingAddressTransfer->getPhone(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return array
     */
    protected function getCustomerAddressData(AddressTransfer $addressTransfer)
    {
        return [
            'firstName' => $addressTransfer->getFirstName(),
            'lastName' => $addressTransfer->getLastName(),
            'company' => $addressTransfer->getCompany(),
            'streetAddress' => trim(sprintf('%s %s', $addressTransfer->getAddress1(), $addressTransfer->getAddress2())),
            'extendedAddress' => $addressTransfer->getAddress3(),
            'locality' => $addressTransfer->getCity(),
            'region' => $addressTransfer->getRegion(),
            'postalCode' => $addressTransfer->getZipCode(),
            'countryCodeAlpha2' => $addressTransfer->getIso2Code(),
        ];
    }

    /**
     * @return float
     */
    protected function getAmount()
    {
        $grandTotal = $this->getQuote()->requireTotals()->getTotals()->getGrandTotal();

        return $this->moneyFacade->convertIntegerToDecimal($grandTotal);
    }

    /**
     * @return string
     */
    protected function getNonce()
    {
        return $this->getBraintreePayment()->requireNonce()->getNonce();
    }

    /**
     * @return \Generated\Shared\Transfer\BraintreePaymentTransfer
     */
    protected function getBraintreePayment()
    {
        return $this->getPayment()->requireBraintree()->getBraintree();
    }

    /**
     * @return string
     */
    protected function getPaymentSelection()
    {
        return $this->getPayment()->requirePaymentSelection()->getPaymentSelection();
    }

    /**
     * Customer is not required for guest checkout, so no `requireCustomer()`
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer()
    {
        return $this->getQuote()->getCustomer();
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPayment()
    {
        return $this->getQuote()->requirePayment()->getPayment();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote()
    {
        return $this->transactionMetaTransfer->requireQuote()->getQuote();
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getBillingAddress()
    {
        return $this->getQuote()->requireBillingAddress()->getBillingAddress();
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShippingAddress()
    {
        return $this->getQuote()->requireShippingAddress()->getShippingAddress();
    }

    /**
     * Prevent logging
     *
     * @return void
     */
    protected function beforeTransaction()
    {
    }

    /**
     * @param \Braintree\Result\Successful|\Braintree\Result\Error|\Braintree\Transaction $response
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    protected function afterTransaction($response)
    {
        if ($this->isTransactionSuccessful($response)) {
            $this->updatePaymentForSuccessfulResponse($response);
            $transaction = $response->transaction;
            $braintreeTransactionResponseTransfer = $this->getSuccessResponseTransfer($response);
            $braintreeTransactionResponseTransfer->setCode($transaction->processorSettlementResponseCode);
            $braintreeTransactionResponseTransfer->setCreditCardType($transaction->creditCardDetails->cardType);
            $braintreeTransactionResponseTransfer->setPaymentType($transaction->paymentInstrumentType);

            return $braintreeTransactionResponseTransfer;
        }

        $this->updatePaymentForErrorResponse($response);

        $braintreeTransactionResponseTransfer = $this->getErrorResponseTransfer($response);

        return $braintreeTransactionResponseTransfer;
    }

    /**
     * @param \Braintree\Result\Successful|\Braintree\Result\Error|\Braintree\Transaction $response
     *
     * @return bool
     */
    protected function isTransactionSuccessful($response)
    {
        return ($response->success && $this->isValidPaymentType($response));
    }

    /**
     * @param \Braintree\Result\Successful $response
     *
     * @return void
     */
    protected function updatePaymentForSuccessfulResponse($response)
    {
        $braintreePaymentTransfer = $this->getBraintreePayment();
        $braintreePaymentTransfer->setPaymentType($response->transaction->paymentInstrumentType);

        if ($braintreePaymentTransfer->getPaymentType() === PaymentInstrumentType::PAYPAL_ACCOUNT) {
            $this->getPayment()->setPaymentMethod(PaymentTransfer::BRAINTREE_PAY_PAL);
            $this->getPayment()->setPaymentProvider(BraintreeConstants::PROVIDER_NAME);
            $this->getPayment()->setPaymentSelection(PaymentTransfer::BRAINTREE_PAY_PAL);
        } elseif ($braintreePaymentTransfer->getPaymentType() === PaymentInstrumentType::CREDIT_CARD) {
            $this->getPayment()->setPaymentMethod(PaymentTransfer::BRAINTREE_CREDIT_CARD);
            $this->getPayment()->setPaymentProvider(BraintreeConstants::PROVIDER_NAME);
            $this->getPayment()->setPaymentSelection(PaymentTransfer::BRAINTREE_CREDIT_CARD);
        }
    }

    /**
     * When error occurs unset nonce, so this cannot be used anymore
     *
     * @param \Braintree\Result\Error $response
     *
     * @return void
     */
    protected function updatePaymentForErrorResponse($response)
    {
        $this->getBraintreePayment()->setNonce('');
    }

    /**
     * @param \Braintree\Result\Successful $response
     *
     * @return bool
     */
    protected function isValidPaymentType($response)
    {
        $returnedType = $response->transaction->paymentInstrumentType;

        $matching = [
            BraintreeConstants::PAYMENT_METHOD_PAY_PAL => PaymentInstrumentType::PAYPAL_ACCOUNT,
            BraintreeConstants::PAYMENT_METHOD_CREDIT_CARD => PaymentInstrumentType::CREDIT_CARD,
        ];

        return ($matching[$this->getPaymentSelection()] === $returnedType);
    }
}
