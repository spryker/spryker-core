<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesOms\Business\SalesOmsBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsEntityManagerInterface getEntityManager()
 */
class SalesOmsFacade extends AbstractFacade implements SalesOmsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithReference(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        return $this->getFactory()
            ->createOrderItemExpander()
            ->expandOrderItemWithReference($salesOrderItemEntityTransfer, $itemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $orderItemReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemTransfer|null
     */
    public function findSalesOrderItemByOrderItemReference(string $orderItemReference): ?SalesOrderItemTransfer
    {
        return $this->getRepository()->findSalesOrderItemByOrderItemReference($orderItemReference);
    }
}
