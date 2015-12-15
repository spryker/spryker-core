<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\ItemGrouperCheckoutConnector\Business\ItemGrouperCheckoutConnectorFacade;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method ItemGrouperCheckoutConnectorFacade getFacade()
 */
class OrderItemGroupingHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
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
