<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdPersistenceFactory getFactory()
 */
class MerchantRelationshipSalesOrderThresholdRepository extends AbstractRepository implements MerchantRelationshipSalesOrderThresholdRepositoryInterface
{
    /**
     * @module MerchantRelationship
     * @module SalesOrderThreshold
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer[]
     */
    public function getMerchantRelationshipSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array {
        if (empty($merchantRelationshipIds)) {
            return [];
        }

        $merchantRelationshipSalesOrderThresholdEntities = $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdQuery()
            ->filterByFkMerchantRelationship_In(array_values($merchantRelationshipIds))
            ->filterByStoreTransfer($storeTransfer)
            ->filterByCurrencyTransfer($currencyTransfer)
            ->joinStore()
            ->joinCurrency()
            ->joinMerchantRelationship()
            ->joinSalesOrderThresholdType()
            ->find();

        if (empty($merchantRelationshipSalesOrderThresholdEntities)) {
            return [];
        }

        $merchantRelationshipSalesOrderThresholdTransfers = [];

        foreach ($merchantRelationshipSalesOrderThresholdEntities as $merchantRelationshipSalesOrderThresholdEntity) {
            $merchantRelationshipSalesOrderThresholdTransfers[] = $this->getFactory()
                ->createMerchantRelationshipSalesOrderThresholdMapper()
                ->mapMerchantRelationshipSalesOrderThresholdEntityToTransfer(
                    $merchantRelationshipSalesOrderThresholdEntity,
                    new MerchantRelationshipSalesOrderThresholdTransfer()
                );
        }

        return $merchantRelationshipSalesOrderThresholdTransfers;
    }
}
