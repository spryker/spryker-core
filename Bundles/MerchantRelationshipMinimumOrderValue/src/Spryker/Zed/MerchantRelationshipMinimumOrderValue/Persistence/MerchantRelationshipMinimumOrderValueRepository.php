<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValuePersistenceFactory getFactory()
 */
class MerchantRelationshipMinimumOrderValueRepository extends AbstractRepository implements MerchantRelationshipMinimumOrderValueRepositoryInterface
{
    /**
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer[]
     */
    public function findThresholdsForMerchantRelationshipIds(array $merchantRelationshipIds): array
    {
        if (empty($merchantRelationshipIds)) {
            return [];
        }

        $merchantRelationshipMinimumOrderValueEntities = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueQuery()
            ->filterByFkMerchantRelationship_In($merchantRelationshipIds)
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
