<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function saveMinimumOrderValue(MinimumOrderValueTransfer $minimumOrderValueTransfer): MinimumOrderValueTransfer
    {
        $this->assertRequiredAttributes($minimumOrderValueTransfer);

        $minimumOrderValueTypeTransfer = $minimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType();
        $storeTransfer = $minimumOrderValueTransfer->getStore();
        $currencyTransfer = $minimumOrderValueTransfer->getCurrency();

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

        if ($minimumOrderValueEntity->getMessageGlossaryKey() === null) {
            $minimumOrderValueEntity->setMessageGlossaryKey(
                $minimumOrderValueTransfer->getThreshold()->getThresholdNotMetMessageGlossaryKey()
            );
        }

        $minimumOrderValueEntity
            ->setValue($minimumOrderValueTransfer->getThreshold()->getThreshold())
            ->setFee($minimumOrderValueTransfer->getThreshold()->getFeeIfThresholdNotMet())
            ->setFkMinOrderValueType(
                $minimumOrderValueTypeTransfer->getIdMinimumOrderValueType()
            )->save();

        return $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueEntityToTransfer(
                $minimumOrderValueEntity,
                $minimumOrderValueTransfer
            );
    }

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveMinimumOrderValueTaxSet(int $idTaxSet): void
    {
        $this->getFactory()
            ->createMinimumOrderValueTaxSetPropelQuery()
            ->findOneOrCreate()
            ->setFkTaxSet($idTaxSet)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(MinimumOrderValueTransfer $minimumOrderValueTransfer): void
    {
        $minimumOrderValueTransfer
            ->requireThreshold()
            ->requireStore()
            ->requireCurrency();

        $minimumOrderValueTransfer->getThreshold()
            ->requireThreshold()
            ->requireThresholdNotMetMessageGlossaryKey()
            ->requireMinimumOrderValueType();

        $minimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType()
            ->requireIdMinimumOrderValueType()
            ->requireThresholdGroup();

        $minimumOrderValueTransfer->getStore()
            ->requireIdStore();

        $minimumOrderValueTransfer->getCurrency()
            ->requireIdCurrency();
    }
}
