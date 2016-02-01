<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Controller;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\Payolution\Business\PayolutionFacade;

/**
 * @method PayolutionFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPaymentsAction(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        return $this->getFacade()->calculateInstallmentPayments($checkoutRequestTransfer);
    }

}
