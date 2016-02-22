<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Checkout\Business\CheckoutBusinessFactory getFactory()
 */
class CheckoutFacade extends AbstractFacade implements CheckoutFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        return $this
            ->getFactory()
            ->createCheckoutWorkflow()
            ->requestCheckout($checkoutRequest);
    }

}
