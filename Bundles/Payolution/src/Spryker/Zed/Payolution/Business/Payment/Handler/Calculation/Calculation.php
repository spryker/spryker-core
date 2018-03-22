<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Payment\Handler\Calculation;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Payolution\Business\Payment\Handler\AbstractPaymentHandler;

class Calculation extends AbstractPaymentHandler implements CalculationInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(QuoteTransfer $quoteTransfer)
    {
        $requestData = $this
            ->getMethodMapper(PayolutionConstants::BRAND_INSTALLMENT)
            ->buildCalculationRequest($quoteTransfer);

        $responseTransfer = $this->sendRequest($requestData);
        $responseTransfer = $this->setHash($responseTransfer, $quoteTransfer->getTotals()->getHash());

        return $responseTransfer;
    }

    /**
     * @param array $requestData
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
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
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $responseTransfer
     * @param string $hash
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    protected function setHash(PayolutionCalculationResponseTransfer $responseTransfer, $hash)
    {
        return $responseTransfer->setTotalsAmountHash($hash);
    }
}
