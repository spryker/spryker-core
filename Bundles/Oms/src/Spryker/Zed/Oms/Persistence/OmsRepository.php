<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 *
 */
class OmsRepository extends AbstractRepository implements OmsRepositoryInterface
{
    /**
     * @param array $keys
     * @param array $stateBlackList
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getMatrixOrderItems(array $keys, array $stateBlackList): array
    {
        $orderItemsMatrix = [];

        $orderItemsMatrixResult = $this->getFactory()->getOmsQueryContainer()
            ->queryMatrixOrderItems($keys, $stateBlackList)
            ->find();

        foreach ($orderItemsMatrixResult as $orderItemsMatrixRow) {
            $idState = $orderItemsMatrixRow[SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE];
            $idProcess = $orderItemsMatrixRow[SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_PROCESS];

            $orderItemsMatrix[$idState][$idProcess][$orderItemsMatrixRow[OmsQueryContainer::DATE_WINDOW]]
                = $orderItemsMatrixRow[OmsQueryContainer::ITEMS_COUNT];
        }

        return $orderItemsMatrix;
    }
}
