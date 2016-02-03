<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Payolution\PayolutionFactory getFactory()
 */
class PayolutionClient extends AbstractClient implements PayolutionClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        return $this
            ->getFactory()
            ->createPayolutionStub()
            ->calculateInstallmentPayments($checkoutRequestTransfer);
    }

    /**
     * @return \Spryker\Client\Payolution\Session\PayolutionSession
     */
    protected function getSession()
    {
        return $this->getFactory()->createPayolutionSession();
    }

    /**
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
     * @return bool
     */
    public function hasInstallmentPaymentsInSession()
    {
        return $this->getSession()->hasInstallmentPayments();
    }

    /**
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function getInstallmentPaymentsFromSession()
    {
        return $this->getSession()->getInstallmentPayments();
    }

    /**
     * @return mixed
     */
    public function removeInstallmentPaymentsFromSession()
    {
        return $this->getSession()->removeInstallmentPayments();
    }

}
