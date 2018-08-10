<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValuePersistenceFactory getFactory()
 */
class MinimumOrderValueRepository extends AbstractRepository implements MinimumOrderValueRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function getMinimumOrderValueTypeByKey(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueTypeTransfer->requireKey();

        $minimumOrderValueTypeEntity = $this->getFactory()
            ->createMinimumOrderValueTypeQuery()
            ->filterByKey($minimumOrderValueTypeTransfer->getKey())
            ->findOne();

        if (!$minimumOrderValueTypeEntity) {
            throw new StrategyNotFoundException($minimumOrderValueTypeTransfer->getKey());
        }

        return $this->getFactory()->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueTypeEntityToTransfer(
                $minimumOrderValueTypeEntity,
                $minimumOrderValueTypeTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $globalMinimumOrderValueEntities = $this->getFactory()
            ->createMinimumOrderValueQuery()
            ->filterByStoreTransfer($storeTransfer)
            ->filterByCurrencyTransfer($currencyTransfer)
            ->joinWithMinimumOrderValueType()
            ->leftJoinWithSpyMinimumOrderValueLocalizedMessage()
            ->find();

        $globalMinimumOrderValueTransfers = [];

        $minimumOrderValueMapper = $this->getFactory()->createMinimumOrderValueMapper();

        foreach ($globalMinimumOrderValueEntities as $globalMinOrderValueEntity) {
            $globalMinimumOrderValueTransfer = $minimumOrderValueMapper->mapGlobalMinimumOrderValueEntityToTransfer(
                $globalMinOrderValueEntity,
                new GlobalMinimumOrderValueTransfer()
            );

            $globalMinimumOrderValueTransfers[] = $globalMinimumOrderValueTransfer;
        }

        return $globalMinimumOrderValueTransfers;
    }
}
