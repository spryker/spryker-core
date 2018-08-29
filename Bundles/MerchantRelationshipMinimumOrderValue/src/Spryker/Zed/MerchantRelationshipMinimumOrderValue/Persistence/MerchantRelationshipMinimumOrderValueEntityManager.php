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
    public function saveMerchantRelationshipMinimumOrderValue(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $this->assertRequiredAttributes($merchantRelationshipMinimumOrderValueTransfer);

        $minimumOrderValueTransfer = $merchantRelationshipMinimumOrderValueTransfer->getThreshold();

        $merchantRelationshipTransfer = $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship();
        $minimumOrderValueTypeTransfer = $minimumOrderValueTransfer->getMinimumOrderValueType();
        $storeTransfer = $merchantRelationshipMinimumOrderValueTransfer->getStore();
        $currencyTransfer = $merchantRelationshipMinimumOrderValueTransfer->getCurrency();

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

        if ($merchantRelationshipMinimumOrderValueEntity->getMessageGlossaryKey() === null) {
            $merchantRelationshipMinimumOrderValueEntity->setMessageGlossaryKey(
                $merchantRelationshipMinimumOrderValueTransfer->getThreshold()->getThresholdNotMetMessageGlossaryKey()
            );
        }

        $merchantRelationshipMinimumOrderValueEntity
            ->setValue($minimumOrderValueTransfer->getThreshold())
            ->setFee($minimumOrderValueTransfer->getFeeIfThresholdNotMet())
            ->setFkMerchantRelationship(
                $merchantRelationshipTransfer->getIdMerchantRelationship()
            )->setFkMinOrderValueType(
                $minimumOrderValueTypeTransfer->getIdMinimumOrderValueType()
            )->save();

        return $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueMapper()
            ->mapMerchantRelationshipMinimumOrderValueEntityToTransfer(
                $merchantRelationshipMinimumOrderValueEntity,
                $merchantRelationshipMinimumOrderValueTransfer
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
            ->requireStore()
            ->requireCurrency()
            ->requireMerchantRelationship()
            ->requireThreshold();

        $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();

        $merchantRelationshipMinimumOrderValueTransfer->getThreshold()
            ->requireMinimumOrderValueType()
            ->requireThresholdNotMetMessageGlossaryKey()
            ->requireThreshold();

        $merchantRelationshipMinimumOrderValueTransfer->getStore()
            ->requireIdStore();

        $merchantRelationshipMinimumOrderValueTransfer->getCurrency()
            ->getIdCurrency();

        $merchantRelationshipMinimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType()
            ->requireKey()
            ->requireThresholdGroup();
    }
}
