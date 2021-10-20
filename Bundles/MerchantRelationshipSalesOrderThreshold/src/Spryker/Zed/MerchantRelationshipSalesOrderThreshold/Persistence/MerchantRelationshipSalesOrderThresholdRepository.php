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
     * @param array<int> $merchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer>
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
            ->joinWithStore()
            ->joinWithCurrency()
            ->joinWithMerchantRelationship()
            ->joinWithSalesOrderThresholdType()
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
                    new MerchantRelationshipSalesOrderThresholdTransfer(),
                );
        }

        return $merchantRelationshipSalesOrderThresholdTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer|null
     */
    public function findMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): ?MerchantRelationshipSalesOrderThresholdTransfer {
        $merchantRelationshipSalesOrderThresholdEntity = $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdQuery()
            ->findOneByIdMerchantRelationshipSalesOrderThreshold(
                $merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold(),
            );

        if (!$merchantRelationshipSalesOrderThresholdEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdMapper()
            ->mapMerchantRelationshipSalesOrderThresholdEntityToTransfer(
                $merchantRelationshipSalesOrderThresholdEntity,
                new MerchantRelationshipSalesOrderThresholdTransfer(),
            );
    }
}
