<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Communication\Plugin;

use Generated\Shared\Sales\OrderItemInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class ProductSkuGroupKeyHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{
    /**
     * @param OrderTransfer           $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }
    }

    /**
     * @param OrderItemInterface $orderItem
     *
     * @return string
     */
    protected function buildGroupKey(OrderItemInterface $orderItem)
    {
        $groupKey = $orderItem->getGroupKey();
        if (empty($groupKey)) {
            return $orderItem->getSku();
        }

        $groupKey = $groupKey . '-' . $orderItem->getSku();

        return $groupKey;
    }
}
