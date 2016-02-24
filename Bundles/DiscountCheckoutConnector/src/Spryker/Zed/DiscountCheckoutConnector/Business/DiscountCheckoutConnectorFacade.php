<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountCheckoutConnector\Business\DiscountCheckoutConnectorBusinessFactory getFactory()
 */
class DiscountCheckoutConnectorFacade extends AbstractFacade implements DiscountCheckoutConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $this->getFactory()->createOrderHydrator()->hydrateOrder($orderTransfer, $checkoutRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveDiscounts(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFactory()->createDiscountSaver()->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

}
