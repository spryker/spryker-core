<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution\Zed;

use Generated\Shared\Transfer\PayolutionCalculationRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class PayolutionStub implements PayolutionStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @param ZedRequestClient $zedRequestClient
     */
    public function __construct(ZedRequestClient $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param PayolutionCalculationRequestTransfer $calculationRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(PayolutionCalculationRequestTransfer $calculationRequestTransfer)
    {
        return $this->zedRequestClient->call('/payolution/gateway/calculate-installment-payments', $calculationRequestTransfer);
    }

}
