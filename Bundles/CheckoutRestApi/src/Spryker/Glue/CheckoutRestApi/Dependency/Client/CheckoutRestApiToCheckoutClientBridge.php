<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Dependency\Client;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CheckoutRestApiToCheckoutClientBridge implements CheckoutRestApiToCheckoutClientInterface
{
    /**
     * @var \Spryker\Client\Checkout\CheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     */
    public function __construct($checkoutClient)
    {
        $this->checkoutClient = $checkoutClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        return $this->checkoutClient->placeOrder($quoteTransfer);
    }
}
