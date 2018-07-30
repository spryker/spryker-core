<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValuePersistenceFactory getFactory()
 */
class MinimumOrderValueRepository extends AbstractRepository implements MinimumOrderValueRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $minOrderValueEntities = $this->getFactory()
            ->createMinimumOrderValueQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->joinWithMinimumOrderValueType()
            ->joinWithStore()
            ->joinWithCurrency()
            ->useSpyMinimumOrderValueLocalizedMessageQuery()
                ->leftJoinWithLocale()
            ->endUse()
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
