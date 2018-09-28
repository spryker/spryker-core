<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;

class InstallmentCalculationTransaction extends BaseTransaction implements QuoteTransactionInterface
{
    public const TRANSACTION_TYPE = ApiConstants::REQUEST_MODEL_CALCULATION_REQUEST;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodName = $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requirePaymentMethod()
            ->getPaymentMethod();

        $paymentMethod = $this->getMethodMapper($paymentMethodName);
        $request = $paymentMethod
            ->calculationRequest($quoteTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo($request, $response, $paymentMethodName);

        $responseTransfer = $this->converterFactory
            ->getInstallmentCalculationResponseConverter($response, $request)
            ->convert();

        return $responseTransfer;
    }

    /**
     * @param string $xmlRequest
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse
     */
    protected function sendRequest($xmlRequest)
    {
        return new CalculationResponse($this->executionAdapter->sendRequest($xmlRequest));
    }
}
