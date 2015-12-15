<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Controller;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\Payolution\Business\PayolutionFacade;

/**
 * @method PayolutionFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPaymentsAction(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        return $this->getFacade()->calculateInstallmentPayments($checkoutRequestTransfer);
    }

}
