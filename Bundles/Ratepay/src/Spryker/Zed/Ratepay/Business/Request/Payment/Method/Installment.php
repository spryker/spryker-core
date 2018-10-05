<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Method;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

/**
 * Ratepay Elv payment method.
 */
class Installment extends AbstractMethod
{
    /**
     * @const Payment method code.
     */
    public const METHOD = RatepayConstants::METHOD_INSTALLMENT;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    public function getPaymentData(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requireRatepayInstallment()
            ->getRatepayInstallment();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function configurationRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        /*
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_CONFIGURATION_REQUEST);
        $this->mapConfigurationData($quoteTransfer, $paymentData);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    public function calculationRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        /*
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_CALCULATION_REQUEST);
        $this->mapCalculationData($quoteTransfer, $paymentData);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return void
     */
    protected function mapPaymentData(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer)
    {
        parent::mapPaymentData($ratepayPaymentRequestTransfer);

        $this->mapperFactory
            ->getInstallmentPaymentMapper($ratepayPaymentRequestTransfer)
            ->map();
        $this->mapperFactory
            ->getInstallmentDetailMapper($ratepayPaymentRequestTransfer)
            ->map();
        if ($ratepayPaymentRequestTransfer->getDebitPayType() == RatepayConstants::DEBIT_PAY_TYPE_DIRECT_DEBIT) {
            $this->mapBankAccountData($ratepayPaymentRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $paymentData
     *
     * @return void
     */
    protected function mapConfigurationData($quoteTransfer, $paymentData)
    {
        $this->mapperFactory->getQuoteHeadMapper($quoteTransfer, $paymentData)->map();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $paymentData
     *
     * @return void
     */
    protected function mapCalculationData($quoteTransfer, $paymentData)
    {
        $this->mapperFactory->getQuoteHeadMapper($quoteTransfer, $paymentData)->map();

        $this->mapperFactory
            ->getInstallmentCalculationMapper($quoteTransfer, $paymentData)
            ->map();
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function getPaymentTransferObject($payment)
    {
        return new RatepayPaymentInstallmentTransfer();
    }
}
