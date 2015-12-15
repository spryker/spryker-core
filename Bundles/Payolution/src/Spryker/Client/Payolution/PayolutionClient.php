<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method PayolutionDependencyContainer getDependencyContainer()
 */
class PayolutionClient extends AbstractClient implements PayolutionClientInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createPayolutionStub()
            ->calculateInstallmentPayments($checkoutRequestTransfer);
    }

}
