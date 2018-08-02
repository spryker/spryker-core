<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

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
        $minimumOrderValueTypeTransfer->requireKey()->requireThresholdGroup();

        $minimumOrderValueTypeEntity = $this->getFactory()
            ->createMinimumOrderValueTypeQuery()
            ->filterByKey($minimumOrderValueTypeTransfer->getKey())
            ->findOneOrCreate();

        if ($minimumOrderValueTypeEntity->getThresholdGroup() !== $minimumOrderValueTypeTransfer->getThresholdGroup()) {
            $minimumOrderValueTypeEntity->setThresholdGroup($minimumOrderValueTypeTransfer->getThresholdGroup())
                ->save();
        }

        $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueTypeEntityToTransfer(
                $minimumOrderValueTypeEntity,
                $minimumOrderValueTypeTransfer
            );

        return $minimumOrderValueTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function setGlobalThreshold(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): GlobalMinimumOrderValueTransfer
    {
        $this->assertRequiredAttributes($globalMinimumOrderValueTransfer);

        $minimumOrderValueTypeTransfer = $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType();
        $storeTransfer = $globalMinimumOrderValueTransfer->getStore();
        $currencyTransfer = $globalMinimumOrderValueTransfer->getCurrency();

        $minimumOrderValueEntity = $this->getFactory()
            ->createMinimumOrderValueQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->useMinimumOrderValueTypeQuery()
                ->filterByThresholdGroup($minimumOrderValueTypeTransfer->getThresholdGroup())
            ->endUse()
            ->findOne();

        if (!$minimumOrderValueEntity) {
            $minimumOrderValueEntity = (new SpyMinimumOrderValue())
                ->setFkStore($storeTransfer->getIdStore())
                ->setFkCurrency($currencyTransfer->getIdCurrency());
        }

        $minimumOrderValueEntity
            ->setValue($globalMinimumOrderValueTransfer->getMinimumOrderValue()->getValue())
            ->setFee($globalMinimumOrderValueTransfer->getMinimumOrderValue()->getFee())
            ->setFkMinOrderValueType(
                $minimumOrderValueTypeTransfer->getIdMinimumOrderValueType()
            )->save();

        return $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapGlobalMinimumOrderValueEntityToTransfer(
                $minimumOrderValueEntity,
                new GlobalMinimumOrderValueTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): void
    {
        $globalMinimumOrderValueTransfer
            ->requireMinimumOrderValue()
            ->requireStore()
            ->requireCurrency();

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()
            ->requireValue()
            ->requireMinimumOrderValueType();

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()
            ->requireIdMinimumOrderValueType()
            ->requireThresholdGroup();

        $globalMinimumOrderValueTransfer->getStore()
            ->requireIdStore();

        $globalMinimumOrderValueTransfer->getCurrency()
            ->requireIdCurrency();
    }
}
