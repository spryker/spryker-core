<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution\Zed;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class PayolutionStub implements PayolutionStubInterface
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedRequestClient
     */
    public function __construct(ZedRequestClient $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(QuoteTransfer $quoteTransfer)
    {
        return $this->zedRequestClient->call('/payolution/gateway/calculate-installment-payments', $quoteTransfer);
    }

}
