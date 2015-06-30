<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Checkout\Model;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Shared\Library\Communication\Response;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;

class CheckoutManager implements CheckoutManagerInterface
{
    /**
     * @var AbstractZedClient
     */
    protected $zedClient;

    /**
     * @param AbstractZedClient $zedClient
     */
    public function __construct(AbstractZedClient $zedClient)
    {
        $this->zedClient = $zedClient;
    }

    /**
     * @param CheckoutRequestInterface $checkoutRequest
     * @return Response
     */
    public function requestCheckout(CheckoutRequestInterface $checkoutRequest)
    {
        $this->zedClient->call('/checkout/sdk/request-checkout', $checkoutRequest, 60);

        return $this->zedClient->getLastResponse();
    }
}
