<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 *
 */
class OmsRepository extends AbstractRepository implements OmsRepositoryInterface
{
    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array
    {
        $orderItemsMatrixResult = $this->getFactory()->getOmsQueryContainer()
            ->queryGroupedMatrixOrderItems($processIds, $stateBlackList)
            ->find();

        return $this->getFactory()
            ->createOrderItemMatrixMapper()
            ->mapOrderItemMatrix($orderItemsMatrixResult->getArrayCopy());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getSalesOrderItemsByIdSalesOrder(int $idSalesOrder): array
    {
        $orderItemEntities = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItemsByIdSalesOrder($idSalesOrder)
            ->find();

        return $this->mapOrderItemEntitiesToItemTransfers($orderItemEntities);
    }

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapOrderItemEntitiesToItemTransfers(iterable $orderItemEntities): array
    {
        $omsOrderItemMapper = $this->getFactory()->createOrderItemMapper();
        $itemTransfers = [];

        foreach ($orderItemEntities as $orderItemEntity) {
            $itemTransfers[] = $omsOrderItemMapper->mapOrderItemEntityToItemTransfer($orderItemEntity, new ItemTransfer());

            /**
             * @todo Call additional shipment mapper or expander.
             */
        }

        return $itemTransfers;
    }
}
