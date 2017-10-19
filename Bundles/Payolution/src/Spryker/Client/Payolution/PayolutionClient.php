<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payolution;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Payolution\PayolutionFactory getFactory()
 */
class PayolutionClient extends AbstractClient implements PayolutionClientInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createPayolutionStub()
            ->calculateInstallmentPayments($quoteTransfer);
    }

    /**
     * @return \Spryker\Client\Payolution\Session\PayolutionSession
     */
    protected function getSession()
    {
        return $this->getFactory()->createPayolutionSession();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function storeInstallmentPaymentsInSession(PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer)
    {
        $this->getSession()->setInstallmentPayments($payolutionCalculationResponseTransfer);

        return $payolutionCalculationResponseTransfer;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function hasInstallmentPaymentsInSession()
    {
        return $this->getSession()->hasInstallmentPayments();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function getInstallmentPaymentsFromSession()
    {
        return $this->getSession()->getInstallmentPayments();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function removeInstallmentPaymentsFromSession()
    {
        return $this->getSession()->removeInstallmentPayments();
    }
}
