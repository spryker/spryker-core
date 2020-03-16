<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsPersistenceFactory getFactory()
 */
class MerchantOmsRepository extends AbstractRepository implements MerchantOmsRepositoryInterface
{
    /**
     * @param int $idState
     *
     * @return int[]
     */
    public function getMerchantOrderItemIdsByIdState(int $idState): array
    {
        return $this->getFactory()->getMerchantSalesOrderItemPropelQuery()
            ->filterByFkStateMachineItemState($idState)
            ->select(SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM)
            ->find()
            ->toArray();
    }
}
