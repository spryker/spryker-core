<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service\Zed;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class CheckoutStub implements CheckoutStubInterface
{

    /**
     * @var ZedClient
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
     * @param CheckoutRequestTransfer $transferCheckout
     *
     * @return CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $transferCheckout)
    {
        return $this->zedStub->call('/checkout/gateway/request-checkout', $transferCheckout);
    }

}
