<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThreshold;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdPersistenceFactory getFactory()
 */
class MerchantRelationshipSalesOrderThresholdEntityManager extends AbstractEntityManager implements MerchantRelationshipSalesOrderThresholdEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function saveMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $this->assertRequiredAttributes($merchantRelationshipSalesOrderThresholdTransfer);

        $salesOrderThresholdValueTransfer = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue();

        $merchantRelationshipTransfer = $merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship();
        $salesOrderThresholdTypeTransfer = $salesOrderThresholdValueTransfer->getSalesOrderThresholdType();
        $storeTransfer = $merchantRelationshipSalesOrderThresholdTransfer->getStore();
        $currencyTransfer = $merchantRelationshipSalesOrderThresholdTransfer->getCurrency();

        $merchantRelationshipSalesOrderThresholdEntity = $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->filterByFkMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship())
            ->useSalesOrderThresholdTypeQuery()
                ->filterByThresholdGroup($salesOrderThresholdTypeTransfer->getThresholdGroup())
            ->endUse()
            ->findOne();

        if (!$merchantRelationshipSalesOrderThresholdEntity) {
            $merchantRelationshipSalesOrderThresholdEntity = (new SpyMerchantRelationshipSalesOrderThreshold())
                ->setFkStore($storeTransfer->getIdStore())
                ->setFkCurrency($currencyTransfer->getIdCurrency());
        }

        if ($merchantRelationshipSalesOrderThresholdEntity->getMessageGlossaryKey() === null) {
            $merchantRelationshipSalesOrderThresholdEntity->setMessageGlossaryKey(
                $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey()
            );
        }

        $merchantRelationshipSalesOrderThresholdEntity
            ->setThreshold($salesOrderThresholdValueTransfer->getThreshold())
            ->setFee($salesOrderThresholdValueTransfer->getFee())
            ->setFkMerchantRelationship(
                $merchantRelationshipTransfer->getIdMerchantRelationship()
            )->setFkSalesOrderThresholdType(
                $salesOrderThresholdTypeTransfer->getIdSalesOrderThresholdType()
            )->save();

        return $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdMapper()
            ->mapMerchantRelationshipSalesOrderThresholdEntityToTransfer(
                $merchantRelationshipSalesOrderThresholdEntity,
                $merchantRelationshipSalesOrderThresholdTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): void
    {
        $merchantRelationshipSalesOrderThresholdTransfer
            ->requireStore()
            ->requireCurrency()
            ->requireMerchantRelationship()
            ->requireSalesOrderThresholdValue();

        $merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();

        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->requireSalesOrderThresholdType()
            ->requireMessageGlossaryKey()
            ->requireThreshold();

        $merchantRelationshipSalesOrderThresholdTransfer->getStore()
            ->requireIdStore();

        $merchantRelationshipSalesOrderThresholdTransfer->getCurrency()
            ->getIdCurrency();

        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()
            ->requireKey()
            ->requireThresholdGroup();
    }
}
