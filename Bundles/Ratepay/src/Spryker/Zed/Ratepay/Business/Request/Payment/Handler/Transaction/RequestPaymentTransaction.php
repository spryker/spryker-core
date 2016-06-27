<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class RequestPaymentTransaction extends BaseTransaction implements QuoteTransactionInterface
{

    const TRANSACTION_TYPE = ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodName = $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requirePaymentMethod()
            ->getPaymentMethod();

        $request = $this->getMethodMapper($paymentMethodName)
            ->paymentRequest($quoteTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo($request, $response, $quoteTransfer->getPayment()->getPaymentMethod());

        $responseTransfer = $this->converterFactory
            ->getTransferObjectConverter($response)
            ->convert();
        $this->fixResponseTransferTransactionId($responseTransfer, $responseTransfer->getTransactionId(), $responseTransfer->getTransactionShortId());

        return $responseTransfer;
    }

}
