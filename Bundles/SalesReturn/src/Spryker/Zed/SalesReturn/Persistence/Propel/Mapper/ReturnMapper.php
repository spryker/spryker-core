<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturn;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnItem;
use Propel\Runtime\Collection\ObjectCollection;

class ReturnMapper
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturn $salesReturnEntity
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturn
     */
    public function mapReturnTransferToSalesReturnEntity(ReturnTransfer $returnTransfer, SpySalesReturn $salesReturnEntity): SpySalesReturn
    {
        $salesReturnEntity->fromArray($returnTransfer->modifiedToArray());

        return $salesReturnEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturnItem $salesReturnItemEntity
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnItem
     */
    public function mapReturnItemTransferToSalesReturnItemEntity(ReturnItemTransfer $returnItemTransfer, SpySalesReturnItem $salesReturnItemEntity): SpySalesReturnItem
    {
        $salesReturnItemEntity->fromArray($returnItemTransfer->modifiedToArray());
        $salesReturnItemEntity
            ->setFkSalesReturn($returnItemTransfer->getIdSalesReturn())
            ->setFkSalesOrderItem($returnItemTransfer->getOrderItem()->getIdSalesOrderItem());

        return $salesReturnItemEntity;
    }
    
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $salesReturnEntities
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function mapReturnEntityCollectionToReturnCollection(ObjectCollection $salesReturnEntities): ReturnCollectionTransfer
    {
        $returnCollectionTransfer = new ReturnCollectionTransfer();

        foreach ($salesReturnEntities as $salesReturnEntity) {
            $returnCollectionTransfer->addReturn(
                (new ReturnTransfer())->fromArray($salesReturnEntity->toArray(), true)
            );
        }

        return $returnCollectionTransfer;
    }
}
