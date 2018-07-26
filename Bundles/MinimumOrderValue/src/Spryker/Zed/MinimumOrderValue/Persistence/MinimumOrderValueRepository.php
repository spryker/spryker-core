<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValuePersistenceFactory getFactory()
 */
class MinimumOrderValueRepository extends AbstractRepository implements MinimumOrderValueRepositoryInterface
{
    /**
     * @param int $storeId
     * @param int $currencyId
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(int $storeId, int $currencyId): array
    {
        $minOrderValueEntities = $this->getFactory()
            ->createMinimumOrderValueQuery()
            ->filterByFkStore($storeId)
            ->filterByFkCurrency($currencyId)
            ->joinWithMinimumOrderValueType()
            ->joinWithStore()
            ->joinWithCurrency()
            ->leftJoinWithSpyMinimumOrderValueLocalizedMessage()
            ->find();

        $orderSourceTransfers = [];

        $mapper = $this->getFactory()->createMinimumOrderValueMapper();

        foreach ($minOrderValueEntities as $minOrderValueEntity) {
            $orderSourceTransfer = $mapper->mapMinimumOrderValueEntityToTransfer(
                $minOrderValueEntity,
                new MinimumOrderValueTransfer()
            );

            $orderSourceTransfers[] = $orderSourceTransfer;
        }

        return $orderSourceTransfers;
    }
}
