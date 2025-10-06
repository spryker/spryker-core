<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business\Reader;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @param \Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeInterface $salesFacade
     * @param list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderOrderProviderPluginInterface> $cartReorderOrderProviderPlugins
     */
    public function __construct(
        protected CartReorderToSalesFacadeInterface $salesFacade,
        protected array $cartReorderOrderProviderPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findCustomerOrder(CartReorderRequestTransfer $cartReorderRequestTransfer): ?OrderTransfer
    {
        $orderListRequestTransfer = (new OrderListRequestTransfer())
            ->setCustomerReference($cartReorderRequestTransfer->getCustomerReferenceOrFail())
            ->addOrderReference($cartReorderRequestTransfer->getOrderReferenceOrFail());

        $orderTransfer = $this->salesFacade
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer)
            ->getOrders()
            ->getIterator()
            ->current();

        if ($orderTransfer) {
            return $orderTransfer;
        }

        return $this->executeCartReorderOrderProviderPlugins($cartReorderRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function executeCartReorderOrderProviderPlugins(
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): ?OrderTransfer {
        foreach ($this->cartReorderOrderProviderPlugins as $cartReorderOrderProviderPlugin) {
            $orderTransfer = $cartReorderOrderProviderPlugin->findOrder($cartReorderRequestTransfer);

            if ($orderTransfer) {
                return $orderTransfer;
            }
        }

        return null;
    }
}
