<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ItemGrouperCheckoutConnector\Business\ItemGrouperCheckoutConnectorFacade getFacade()
 * @method \Spryker\Zed\ItemGrouperCheckoutConnector\Communication\ItemGrouperCheckoutConnectorCommunicationFactory getFactory()
 */
class OrderItemGroupingHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $groupAbleItems = new GroupableContainerTransfer();
        $groupAbleItems->setItems($orderTransfer->getItems());
        $groupedOrderItems = $this->getFacade()->groupOrderItems($groupAbleItems);
        $orderTransfer->setItems($groupedOrderItems->getItems());
    }

}
