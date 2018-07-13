<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValuePersistenceFactory getFactory()
 */
class MinimumOrderValueEntityManager extends AbstractEntityManager implements MinimumOrderValueEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function saveMinimumOrderValueType(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueTypeTransfer->requireName();

        $minimumOrderValueTypeEntity = $this->getFactory()
            ->createMinimumOrderValueTypeQuery()
            ->filterByName($minimumOrderValueTypeTransfer->getName())
            ->findOneOrCreate();

        $minimumOrderValueTypeEntity->save();

        $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueTypeTransfer(
                $minimumOrderValueTypeEntity,
                $minimumOrderValueTypeTransfer
            );

        return $minimumOrderValueTypeTransfer;
    }

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        MinimumOrderValueStrategyInterface $minimumOrderValueStrategy,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $value,
        ?int $fee = null
    ): MinimumOrderValueTransfer {
        $minimumOrderValueTypeTransfer = $this->saveMinimumOrderValueType($minimumOrderValueStrategy->toTransfer());

        $storeTransfer->requireIdStore();
        $currencyTransfer->requireIdCurrency();

        $minimumOrderValueEntity = $this->getFactory()
            ->createMinimumOrderValueQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByThresholdGroup($minimumOrderValueStrategy->getGroup())
            ->findOneOrCreate();

        $minimumOrderValueEntity = $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueEntity(
                new MinimumOrderValueTransfer(),
                $minimumOrderValueEntity
            );

        $minimumOrderValueEntity
            ->setFkStore($storeTransfer->getIdStore())
            ->getMinimumOrderValueAttribute()
            ->setFkMinOrderValueType($minimumOrderValueTypeTransfer->getIdMinimumOrderValueType())
            ->setValue($value)
            ->setFee($fee)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->save();

        $minimumOrderValueTransfer = $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueTransfer(
                $minimumOrderValueEntity,
                new MinimumOrderValueTransfer()
            );

        return $minimumOrderValueTransfer;
    }
}
