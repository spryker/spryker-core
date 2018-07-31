<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValuePersistenceFactory getFactory()
 */
class MerchantRelationshipMinimumOrderValueEntityManager extends AbstractEntityManager implements MerchantRelationshipMinimumOrderValueEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $this->assertRequiredAttributes($merchantRelationshipMinimumOrderValueTransfer);

        $minimumOrderValueTransfer = $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue();

        $merchantRelationshipTransfer = $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship();
        $minimumOrderValueTypeTransfer = $minimumOrderValueTransfer->getMinimumOrderValueType();
        $storeTransfer = $minimumOrderValueTransfer->getStore();
        $currencyTransfer = $minimumOrderValueTransfer->getCurrency();

        $merchantRelationshipMinimumOrderValueEntity = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->filterByFkMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship())
            ->useMinimumOrderValueTypeQuery()
                ->filterByThresholdGroup($minimumOrderValueTypeTransfer->getThresholdGroup())
            ->endUse()
            ->findOne();

        if (!$merchantRelationshipMinimumOrderValueEntity) {
            $merchantRelationshipMinimumOrderValueEntity = (new SpyMerchantRelationshipMinimumOrderValue())
                ->setFkStore($storeTransfer->getIdStore())
                ->setFkCurrency($currencyTransfer->getIdCurrency());
        }

        $merchantRelationshipMinimumOrderValueEntity
            ->setValue($minimumOrderValueTransfer->getValue())
            ->setFee($minimumOrderValueTransfer->getFee())
            ->setFkMerchantRelationship(
                $merchantRelationshipTransfer->getIdMerchantRelationship()
            )->setFkMinOrderValueType(
                $minimumOrderValueTypeTransfer->getIdMinimumOrderValueType()
            )->save();

        return $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueMapper()
            ->mapMerchantRelationshipMinimumOrderValueEntityToTransfer(
                $merchantRelationshipMinimumOrderValueEntity,
                new MerchantRelationshipMinimumOrderValueTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): void
    {
        $merchantRelationshipMinimumOrderValueTransfer
            ->requireMerchantRelationship()
            ->requireMinimumOrderValue();

        $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()
            ->requireMinimumOrderValueType()
            ->requireStore()
            ->requireCurrency()
            ->requireValue();

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getStore()
            ->requireIdStore();

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getCurrency()
            ->getIdCurrency();

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()
            ->requireKey()
            ->requireThresholdGroup();
    }
}
