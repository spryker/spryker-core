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
     * @var \Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeInterface
     */
    protected CartReorderToSalesFacadeInterface $salesFacade;

    /**
     * @param \Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeInterface $salesFacade
     */
    public function __construct(CartReorderToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
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

        return $this->salesFacade
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer)
            ->getOrders()
            ->getIterator()
            ->current();
    }
}
