<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method\PayPal;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Spryker\Zed\Braintree\Business\Payment\Method\AbstractPaymentMethod;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;

class PayPal extends AbstractPaymentMethod implements PayPalInterface
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return ApiConstants::METHOD_PAY_PAL;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(QuoteTransfer $quoteTransfer)
    {
        $braintreeTransfer = $quoteTransfer->getPayment()->getBraintree();
        $addressTransfer = $braintreeTransfer->getAddress();

        $requestData = $this->getBaseTransactionRequest(
            $quoteTransfer->getTotals()->getGrandTotal(),
            $braintreeTransfer->getCurrencyIso3Code(),
            $isSalesOrder = null
        );
        $this->addRequestData(
            $requestData,
            [
            ]
        );

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     *
     * @return array
     */
    public function buildPreAuthorizationRequest(OrderTransfer $orderTransfer, SpyPaymentBraintree $paymentEntity)
    {
        $requestData = $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION,
            null
        );
        $this->addRequestData(
            $requestData,
            [
            ]
        );

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildReAuthorizationRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentBraintree $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION,
            $uniqueId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentBraintree $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_REVERSAL,
            $uniqueId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentBraintree $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_CAPTURE,
            $uniqueId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRefundRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentBraintree $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_REFUND,
            $uniqueId
        );
    }

}
