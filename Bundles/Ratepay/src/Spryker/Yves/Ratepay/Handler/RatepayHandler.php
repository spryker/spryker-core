<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Ratepay\RatepayClientInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Component\HttpFoundation\Request;

class RatepayHandler
{
    public const INSTALLMENT_CALCULATOR_ERROR_HASH = 0;
    public const CHECKOUT_PARTIAL_SUMMARY_PATH = 'Ratepay/partial/summary';

    /**
     * @var \Spryker\Client\Ratepay\RatepayClientInterface
     */
    protected $ratepayClient;

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @var array
     */
    protected static $paymentMethodMapper = [
        RatepayConstants::PAYMENT_METHOD_INVOICE => RatepayConstants::INVOICE,
        RatepayConstants::PAYMENT_METHOD_ELV => RatepayConstants::ELV,
        RatepayConstants::PAYMENT_METHOD_PREPAYMENT => RatepayConstants::PREPAYMENT,
        RatepayConstants::PAYMENT_METHOD_INSTALLMENT => RatepayConstants::INSTALLMENT,
    ];

    /**
     * @var array
     */
    protected static $genderMapper = [
        'Mr' => RatepayConstants::GENDER_MALE,
        'Mrs' => RatepayConstants::GENDER_FEMALE,
    ];

    /**
     * @param \Spryker\Client\Ratepay\RatepayClientInterface $ratepayClient
     */
    public function __construct(RatepayClientInterface $ratepayClient)
    {
        $this->ratepayClient = $ratepayClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer, FlashMessengerInterface $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;

        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();
        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setRatepayPayment($request, $quoteTransfer, $paymentSelection);
        $this->calculateInstallmentPlan($quoteTransfer);
        $this->setPaymentSuccessPartialPath($quoteTransfer);

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
            ->setPaymentProvider(RatepayConstants::PROVIDER_NAME)
            ->setPaymentMethod(static::$paymentMethodMapper[$paymentSelection]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setRatepayPayment(Request $request, QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $ratepayPaymentTransfer = $this->getPaymentTransfer($quoteTransfer, $paymentSelection);
        $ratepayPaymentTransfer->setPaymentType(static::$paymentMethodMapper[$paymentSelection]);

        $billingAddress = $quoteTransfer->getBillingAddress();

        $ratepayPaymentTransfer
            ->setGender(static::$genderMapper[$billingAddress->getSalutation()])
            ->setCurrencyIso3($this->getCurrency())
            ->setIpAddress($request->getClientIp())
            ->setDeviceFingerprint(
                md5(
                    $quoteTransfer->getCustomer()->getIdCustomer()
                    . "_"
                    . microtime()
                )
            )
            ->setDeviceIdentSId(Config::get(RatepayConstants::SNIPPET_ID));
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
     * @return \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $method = 'get' . ucfirst($paymentSelection);
        $paymentTransfer = $quoteTransfer->getPayment()->$method();

        return $paymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function calculateInstallmentPlan(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment()->getPaymentSelection() === RatepayConstants::PAYMENT_METHOD_INSTALLMENT) {
            $calculationResponse = $this->ratepayClient->installmentCalculation($quoteTransfer);
            if ($calculationResponse->getBaseResponse()->getSuccessful()) {
                $this->setInstallmentPlanDetailToQuote($quoteTransfer, $calculationResponse);
            } else {
                $this->setInstallmentCalculatorError($quoteTransfer, $calculationResponse);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setPaymentSuccessPartialPath(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requirePayment()
            ->getPayment()->setSummaryPartialPath(self::CHECKOUT_PARTIAL_SUMMARY_PATH);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer $calculationResponse
     *
     * @return void
     */
    protected function setInstallmentPlanDetailToQuote(QuoteTransfer $quoteTransfer, $calculationResponse)
    {
        $quoteTransfer->getPayment()
            ->getRatepayInstallment()
            ->setInstallmentAnnualPercentageRate($calculationResponse->getAnnualPercentageRate())
            ->setInstallmentInterestRate($calculationResponse->getInterestRate())
            ->setInstallmentLastRate($calculationResponse->getLastRate())
            ->setInstallmentServiceCharge($calculationResponse->getServiceCharge())
            ->setInstallmentNumberRates($calculationResponse->getNumberOfRates())
            ->setInstallmentRate($calculationResponse->getRate())
            ->setInstallmentInterestAmount($calculationResponse->getInterestAmount())
            ->setInstallmentGrandTotalAmount($calculationResponse->getTotalAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer $calculationResponse
     *
     * @return void
     */
    protected function setInstallmentCalculatorError(QuoteTransfer $quoteTransfer, $calculationResponse)
    {
        $quoteTransfer->getPayment()->setPaymentProvider(null);

        $this->flashMessenger->addErrorMessage(
            $calculationResponse
                ->requireBaseResponse()
                ->getBaseResponse()
                ->requireReasonText()
                ->getReasonText()
        );
    }
}
