<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction;

use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class InitPaymentTransaction extends BaseTransaction implements PaymentInitTransactionInterface
{
    public const TRANSACTION_TYPE = ApiConstants::REQUEST_MODEL_PAYMENT_INIT;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function request(RatepayPaymentInitTransfer $ratepayPaymentInitTransfer)
    {
        $paymentMethodName = $ratepayPaymentInitTransfer->getPaymentMethodName();

        $paymentMethod = $this->getMethodMapper($paymentMethodName);

        $request = $paymentMethod
            ->paymentInit($ratepayPaymentInitTransfer);
        $response = $this->sendRequest((string)$request);
        $this->logInfo($request, $response, $paymentMethodName);

        $initResponseTransfer = $this->converterFactory
            ->getTransferObjectConverter($response)
            ->convert();
        if ($initResponseTransfer->getSuccessful()) {
            $ratepayPaymentInitTransfer
                ->setTransactionId($initResponseTransfer->requireTransactionId()->getTransactionId())
                ->setTransactionShortId($initResponseTransfer->requireTransactionShortId()->getTransactionShortId());
        }

        return $initResponseTransfer;
    }
}
