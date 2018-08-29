<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValuePersistenceFactory getFactory()
 */
class MerchantRelationshipMinimumOrderValueRepository extends AbstractRepository implements MerchantRelationshipMinimumOrderValueRepositoryInterface
{
    /**
     * @module MerchantRelationship
     * @module MinimumOrderValue
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer[]
     */
    public function findThresholdsForMerchantRelationshipIds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array {
        if (empty($merchantRelationshipIds)) {
            return [];
        }

        $merchantRelationshipMinimumOrderValueEntities = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueQuery()
            ->filterByFkMerchantRelationship_In(array_values($merchantRelationshipIds))
            ->filterByStoreTransfer($storeTransfer)
            ->filterByCurrencyTransfer($currencyTransfer)
            ->joinStore()
            ->joinCurrency()
            ->joinMerchantRelationship()
            ->joinMinimumOrderValueType()
            ->find();

        if (empty($merchantRelationshipMinimumOrderValueEntities)) {
            return [];
        }

        $merchantRelationshipMinimumOrderValueTransfers = [];

        foreach ($merchantRelationshipMinimumOrderValueEntities as $merchantRelationshipMinimumOrderValueEntity) {
            $merchantRelationshipMinimumOrderValueTransfers[] = $this->getFactory()
                ->createMerchantRelationshipMinimumOrderValueMapper()
                ->mapMerchantRelationshipMinimumOrderValueEntityToTransfer(
                    $merchantRelationshipMinimumOrderValueEntity,
                    new MerchantRelationshipMinimumOrderValueTransfer()
                );
        }

        return $merchantRelationshipMinimumOrderValueTransfers;
    }
}
