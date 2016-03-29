<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Handler\Calculation;

use Generated\Shared\Transfer\BraintreeCalculationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\Business\Payment\Handler\AbstractPaymentHandler;

class Calculation extends AbstractPaymentHandler implements CalculationInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(QuoteTransfer $quoteTransfer)
    {
        $requestData = $this
            ->getMethodMapper(BraintreeConstants::METHOD_PAY_PAL)
            ->buildCalculationRequest($quoteTransfer);

        $responseTransfer = $this->sendRequest($requestData);
        $responseTransfer = $this->setHash($responseTransfer, $quoteTransfer->getTotals()->getHash());

        return $responseTransfer;
    }

    /**
     * @param array $requestData
     *
     * @return \Generated\Shared\Transfer\BraintreeCalculationResponseTransfer
     */
    protected function sendRequest($requestData)
    {
        $calculationRequest = $this->converter->toCalculationRequest($requestData);
        $responseData = $this->executionAdapter->sendAuthorizedRequest(
            $calculationRequest,
            $this->getConfig()->getCalculationUserLogin(),
            $this->getConfig()->getCalculationUserPassword()
        );
        $responseTransfer = $this->converter->toCalculationResponseTransfer($responseData);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BraintreeCalculationResponseTransfer $responseTransfer
     * @param string $hash
     *
     * @return \Generated\Shared\Transfer\BraintreeCalculationResponseTransfer
     */
    protected function setHash(BraintreeCalculationResponseTransfer $responseTransfer, $hash)
    {
        return $responseTransfer->setTotalsAmountHash($hash);
    }

}
