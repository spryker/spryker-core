<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Oms\Business\Process\State;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 *
 */
class OmsRepository extends AbstractRepository implements OmsRepositoryInterface
{
    protected const SUM_COLUMN = 'SUM';

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
            ->createOrderItemMapper()
            ->mapOrderItemMatrix($orderItemsMatrixResult->getArrayCopy());
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\State[] $states
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getSalesOrderItemsBySkuAndStatesNames(array $states, string $sku, ?StoreTransfer $storeTransfer): array
    {
        $stateNames = array_unique($this->mapStatesToStateNames($states));

        $salesOrderItemQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->select([
                SpySalesOrderItemTableMap::COL_SKU,
                SpyOmsOrderProcessTableMap::COL_NAME,
                SpyOmsOrderItemStateTableMap::COL_NAME,
            ])->filterBySku($sku)
            ->innerJoinProcess()
            ->useStateQuery()
                ->filterByName_In($stateNames)
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->groupByFkOmsOrderProcess()
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_QUANTITY . ')', static::SUM_COLUMN);

        if ($storeTransfer !== null) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore($storeTransfer->getName())
                ->endUse();
        }

        $itemTransfers = [];
        foreach ($salesOrderItemQuery->find() as $salesOrderItemEntityArray) {
            $salesOrderItemEntityArray[SpySalesOrderItemTableMap::COL_QUANTITY] = $salesOrderItemEntityArray[static::SUM_COLUMN];
            $itemTransfers[] = $this->getFactory()
                ->createOrderItemMapper()
                ->mapOrderItemEntityArrayToItemTransfer($salesOrderItemEntityArray, new ItemTransfer());
        }

        return $itemTransfers;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\State[] $states
     *
     * @return string[]
     */
    protected function mapStatesToStateNames(array $states): array
    {
        return array_map(function (State $state) {
            return $state->getName();
        }, $states);
    }
}
