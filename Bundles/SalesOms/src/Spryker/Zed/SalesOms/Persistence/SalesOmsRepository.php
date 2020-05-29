<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Persistence;

use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsPersistenceFactory getFactory()
 */
class SalesOmsRepository extends AbstractRepository implements SalesOmsRepositoryInterface
{
    /**
     * @param string $orderItemReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemTransfer|null
     */
    public function findSalesOrderItemByOrderItemReference(string $orderItemReference): ?SalesOrderItemTransfer
    {
        $salesOrderItemEntity = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->findOneByOrderItemReference($orderItemReference);

        if (!$salesOrderItemEntity) {
            return null;
        }

        return $this->getFactory()
            ->createSalesOmsMapper()
            ->mapSalesOrderItemEntityToSalesOrderItemTransfer($salesOrderItemEntity, new SalesOrderItemTransfer());
    }
}
