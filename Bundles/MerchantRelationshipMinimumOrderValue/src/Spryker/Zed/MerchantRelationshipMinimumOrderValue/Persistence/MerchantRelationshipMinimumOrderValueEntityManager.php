<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValuePersistenceFactory getFactory()
 */
class MerchantRelationshipMinimumOrderValueEntityManager extends AbstractEntityManager implements MerchantRelationshipMinimumOrderValueEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $minimumOrderValueTypeTransfer->requireIdMinimumOrderValueType()->requireThresholdGroup();
        $merchantRelationshipTransfer->requireIdMerchantRelationship();
        $storeTransfer->requireIdStore();
        $currencyTransfer->requireIdCurrency();

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
            ->setValue($thresholdValue)
            ->setFee($fee)
            ->setFkMerchantRelationship(
                $merchantRelationshipTransfer->getIdMerchantRelationship()
            )->setFkMinOrderValueType(
                $minimumOrderValueTypeTransfer->getIdMinimumOrderValueType()
            )->save();

        $merchantRelationshipMinimumOrderValueTransfer = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueMapper()
            ->mapMerchantRelationshipMinimumOrderValueTransfer(
                $merchantRelationshipMinimumOrderValueEntity,
                new MerchantRelationshipMinimumOrderValueTransfer()
            );

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
