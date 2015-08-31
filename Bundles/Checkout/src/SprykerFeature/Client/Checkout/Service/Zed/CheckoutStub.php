<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service\Zed;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;

class CheckoutStub implements CheckoutStubInterface
{

    /**
     * @var ZedClient
     */
    private $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param CheckoutRequestInterface $transferCheckout
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckout(CheckoutRequestInterface $transferCheckout)
    {
        return $this->zedStub->call('/checkout/gateway/request-checkout', $transferCheckout);
    }

}
