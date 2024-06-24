<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;
use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission;
use Propel\Runtime\Collection\Collection;

class SalesMerchantCommissionMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
     * @param \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity
     *
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission
     */
    public function mapSalesMerchantCommissionTransferToSalesMerchantCommissionEntity(
        SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer,
        SpySalesMerchantCommission $salesMerchantCommissionEntity
    ): SpySalesMerchantCommission {
        $salesMerchantCommissionEntity
            ->fromArray($salesMerchantCommissionTransfer->modifiedToArray())
            ->setFkSalesOrder($salesMerchantCommissionTransfer->getIdSalesOrderOrFail());

        if ($salesMerchantCommissionTransfer->getIdSalesOrderItem()) {
            $salesMerchantCommissionEntity->setFkSalesOrderItem($salesMerchantCommissionTransfer->getIdSalesOrderItemOrFail());
        }

        return $salesMerchantCommissionEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission>|\Propel\Runtime\Collection\Collection<\Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission> $salesMerchantCommissionEntities
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer $salesMerchantCommissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function mapSalesMerchantCommissionEntityCollectionToSalesMerchantCommissionCollectionTransfer(
        Collection $salesMerchantCommissionEntities,
        SalesMerchantCommissionCollectionTransfer $salesMerchantCommissionCollectionTransfer
    ): SalesMerchantCommissionCollectionTransfer {
        foreach ($salesMerchantCommissionEntities as $salesMerchantCommissionEntity) {
            $salesMerchantCommissionTransfer = $this->mapSalesMerchantCommissionEntityToSalesMerchantCommissionTransfer(
                $salesMerchantCommissionEntity,
                new SalesMerchantCommissionTransfer(),
            );

            $salesMerchantCommissionCollectionTransfer->addSalesMerchantCommission($salesMerchantCommissionTransfer);
        }

        return $salesMerchantCommissionCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionTransfer
     */
    public function mapSalesMerchantCommissionEntityToSalesMerchantCommissionTransfer(
        SpySalesMerchantCommission $salesMerchantCommissionEntity,
        SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
    ): SalesMerchantCommissionTransfer {
        return $salesMerchantCommissionTransfer
            ->fromArray($salesMerchantCommissionEntity->toArray(), true)
            ->setIdSalesOrder($salesMerchantCommissionEntity->getFkSalesOrder())
            ->setIdSalesOrderItem($salesMerchantCommissionEntity->getFkSalesOrderItem());
    }
}
