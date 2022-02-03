<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThreshold;

class MerchantRelationshipSalesOrderThresholdMapper implements MerchantRelationshipSalesOrderThresholdMapperInterface
{
    /**
     * @param \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThreshold $salesOrderThresholdEntity
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function mapMerchantRelationshipSalesOrderThresholdEntityToTransfer(
        SpyMerchantRelationshipSalesOrderThreshold $salesOrderThresholdEntity,
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $salesOrderThresholdTypeEntity = $salesOrderThresholdEntity->getSalesOrderThresholdType();
        $merchantRelationshipSalesOrderThresholdTransfer->setSalesOrderThresholdValue(
            $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue() ?? (new SalesOrderThresholdValueTransfer()),
        );

        $merchantRelationshipSalesOrderThresholdTransfer->fromArray($salesOrderThresholdEntity->toArray(), true)
            ->setIdMerchantRelationshipSalesOrderThreshold($salesOrderThresholdEntity->getIdMerchantRelationshipSalesOrderThreshold())
            ->setSalesOrderThresholdValue(
                $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->fromArray($salesOrderThresholdEntity->toArray(), true)
                    ->setSalesOrderThresholdType(
                        (new SalesOrderThresholdTypeTransfer())->fromArray($salesOrderThresholdTypeEntity->toArray(), true)
                            ->setIdSalesOrderThresholdType($salesOrderThresholdTypeEntity->getIdSalesOrderThresholdType()),
                    ),
            )->setStore(
                (new StoreTransfer())->fromArray($salesOrderThresholdEntity->getStore()->toArray(), true),
            )->setCurrency(
                (new CurrencyTransfer())->fromArray($salesOrderThresholdEntity->getCurrency()->toArray(), true),
            )->setMerchantRelationship(
                (new MerchantRelationshipTransfer())
                    ->fromArray($salesOrderThresholdEntity->getMerchantRelationship()->toArray(), true),
            );

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
