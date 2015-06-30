<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Checkout\Model;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use SprykerFeature\Shared\Library\Communication\Response;
use Generated\Shared\Transfer\OrderTransfer;

interface CheckoutManagerInterface
{
    /**
     * @param CheckoutRequestInterface $checkoutRequest
     * @return Response
     */
    public function requestCheckout(CheckoutRequestInterface $checkoutRequest);
}
