<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartCheckoutConnector\Business\CartCheckoutConnectorFacade getFacade()
 * @method \Spryker\Zed\CartCheckoutConnector\Communication\CartCheckoutConnectorCommunicationFactory getFactory()
 */
class ProductSkuGroupKeyHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
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
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItem
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
