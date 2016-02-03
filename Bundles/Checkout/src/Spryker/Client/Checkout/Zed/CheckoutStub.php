<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Checkout\Zed;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CheckoutStub implements CheckoutStubInterface
{

    /**
     * @var \Spryker\Client\ZedRequest\Client\ZedClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $transferCheckout
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $transferCheckout)
    {
        return $this->zedStub->call('/checkout/gateway/request-checkout', $transferCheckout);
    }

}
