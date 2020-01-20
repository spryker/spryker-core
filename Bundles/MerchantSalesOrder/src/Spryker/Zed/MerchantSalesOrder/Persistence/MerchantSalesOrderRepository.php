<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantSalesOrderTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderPersistenceFactory getFactory()
 */
class MerchantSalesOrderRepository extends AbstractRepository implements MerchantSalesOrderRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer $merchantSalesOrderCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer|null
     */
    public function findOne(MerchantSalesOrderCriteriaFilterTransfer $merchantSalesOrderCriteriaFilterTransfer): ?MerchantSalesOrderTransfer
    {
        $merchantSalesOrderQuery = $this->getFactory()->createMerchantSalesOrderQuery();
        $merchantSalesOrderEntity = $this->applyFilters($merchantSalesOrderQuery, $merchantSalesOrderCriteriaFilterTransfer)->findOne();

        if (!$merchantSalesOrderEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderEntityToMerchantSalesOrderTransfer($merchantSalesOrderEntity, new MerchantSalesOrderTransfer());
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer $merchantSalesOrderCriteriaFilterTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyFilters(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantSalesOrderCriteriaFilterTransfer $merchantSalesOrderCriteriaFilterTransfer
    ): SpyMerchantSalesOrderQuery {
        if ($merchantSalesOrderCriteriaFilterTransfer->getMerchantSalesOrderReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantSalesOrderReference($merchantSalesOrderCriteriaFilterTransfer->getMerchantSalesOrderReference());
        }

        return $merchantSalesOrderQuery;
    }
}
