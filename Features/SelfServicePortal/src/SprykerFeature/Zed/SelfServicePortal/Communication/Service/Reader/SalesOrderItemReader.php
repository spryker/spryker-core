<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

class SalesOrderItemReader implements SalesOrderItemReaderInterface
{
    public function __construct(protected SalesFacadeInterface $salesFacade)
    {
    }

    public function findOrderItemById(int $idSalesOrderItem): ?ItemTransfer
    {
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($idSalesOrderItem);

        $itemCollectionTransfer = $this->salesFacade->getOrderItems($orderItemFilterTransfer);
        if ($itemCollectionTransfer->getItems()->count() === 0) {
            return null;
        }

        return $itemCollectionTransfer->getItems()->offsetGet(0);
    }
}
