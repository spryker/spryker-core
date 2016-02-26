<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Checkout\CheckoutFactory getFactory()
 */
class CheckoutClient extends AbstractClient implements CheckoutClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        return $this->getZedStub()->requestCheckout($checkoutRequest);
    }

    /**
     * @return \Spryker\Client\Checkout\Zed\CheckoutStub
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

}
