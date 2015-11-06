<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Communication\Plugin;

use Generated\Shared\Sales\ItemInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class ProductSkuGroupKeyHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }
    }

    /**
     * @param ItemInterface $orderItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemInterface $orderItem)
    {
        $groupKey = $orderItem->getGroupKey();
        if (empty($groupKey)) {
            return $orderItem->getSku();
        }

        return $groupKey;
    }

}
