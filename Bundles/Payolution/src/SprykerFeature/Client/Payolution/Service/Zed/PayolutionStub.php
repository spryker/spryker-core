<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payolution\Service\Zed;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class PayolutionStub implements PayolutionStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        return $this->zedStub->call('/payolution/gateway/calculate-installment-payments', $checkoutRequestTransfer);
    }

}
