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
     * @param \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThreshold $merchantRelationshipSalesOrderThresholdEntity
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function mapMerchantRelationshipSalesOrderThresholdEntityToTransfer(
        SpyMerchantRelationshipSalesOrderThreshold $merchantRelationshipSalesOrderThresholdEntity,
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $salesOrderThresholdTypeEntity = $merchantRelationshipSalesOrderThresholdEntity->getSalesOrderThresholdType();
        $merchantRelationshipSalesOrderThresholdTransfer->setSalesOrderThresholdValue(
            $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue() ?? (new SalesOrderThresholdValueTransfer()),
        );

        $merchantRelationshipSalesOrderThresholdTransfer->fromArray($merchantRelationshipSalesOrderThresholdEntity->toArray(), true)
            ->setIdMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdEntity->getIdMerchantRelationshipSalesOrderThreshold())
            ->setSalesOrderThresholdValue(
                $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->fromArray($merchantRelationshipSalesOrderThresholdEntity->toArray(), true)
                    ->setSalesOrderThresholdType(
                        (new SalesOrderThresholdTypeTransfer())->fromArray($salesOrderThresholdTypeEntity->toArray(), true)
                            ->setIdSalesOrderThresholdType($salesOrderThresholdTypeEntity->getIdSalesOrderThresholdType()),
                    ),
            )->setStore(
                (new StoreTransfer())->fromArray($merchantRelationshipSalesOrderThresholdEntity->getStore()->toArray(), true),
            )->setCurrency(
                (new CurrencyTransfer())->fromArray($merchantRelationshipSalesOrderThresholdEntity->getCurrency()->toArray(), true),
            )->setMerchantRelationship(
                (new MerchantRelationshipTransfer())
                    ->fromArray($merchantRelationshipSalesOrderThresholdEntity->getMerchantRelationship()->toArray(), true),
            );

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
