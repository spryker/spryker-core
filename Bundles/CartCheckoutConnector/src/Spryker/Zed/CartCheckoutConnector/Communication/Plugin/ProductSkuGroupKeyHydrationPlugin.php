<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CartCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ProductSkuGroupKeyHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }
    }

    /**
     * @param ItemTransfer $orderItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $orderItem)
    {
        $groupKey = $orderItem->getGroupKey();
        if (empty($groupKey)) {
            return $orderItem->getSku();
        }

        return $groupKey;
    }

}
