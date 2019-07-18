<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdPersistenceFactory getFactory()
 */
class SalesOrderThresholdEntityManager extends AbstractEntityManager implements SalesOrderThresholdEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function saveSalesOrderThresholdType(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer {
        $salesOrderThresholdTypeTransfer->requireKey()->requireThresholdGroup();

        $salesOrderThresholdTypeEntity = $this->getFactory()
            ->createSalesOrderThresholdTypeQuery()
            ->filterByKey($salesOrderThresholdTypeTransfer->getKey())
            ->findOneOrCreate();

        if ($salesOrderThresholdTypeEntity->getThresholdGroup() !== $salesOrderThresholdTypeTransfer->getThresholdGroup()) {
            $salesOrderThresholdTypeEntity->setThresholdGroup($salesOrderThresholdTypeTransfer->getThresholdGroup())
                ->save();
        }

        $this->getFactory()
            ->createSalesOrderThresholdMapper()
            ->mapSalesOrderThresholdTypeEntityToTransfer(
                $salesOrderThresholdTypeEntity,
                $salesOrderThresholdTypeTransfer
            );

        return $salesOrderThresholdTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer
    {
        $this->assertRequiredAttributes($salesOrderThresholdTransfer);

        $salesOrderThresholdTypeTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType();
        $storeTransfer = $salesOrderThresholdTransfer->getStore();
        $currencyTransfer = $salesOrderThresholdTransfer->getCurrency();

        $salesOrderThresholdEntity = $this->getFactory()
            ->createSalesOrderThresholdQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->useSalesOrderThresholdTypeQuery()
                ->filterByThresholdGroup($salesOrderThresholdTypeTransfer->getThresholdGroup())
            ->endUse()
            ->findOne();

        if (!$salesOrderThresholdEntity) {
            $salesOrderThresholdEntity = (new SpySalesOrderThreshold())
                ->setFkStore($storeTransfer->getIdStore())
                ->setFkCurrency($currencyTransfer->getIdCurrency());
        }

        if ($salesOrderThresholdEntity->getMessageGlossaryKey() === null) {
            $salesOrderThresholdEntity->setMessageGlossaryKey(
                $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey()
            );
        }

        $salesOrderThresholdEntity
            ->setThreshold($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold())
            ->setFee($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getFee())
            ->setFkSalesOrderThresholdType(
                $salesOrderThresholdTypeTransfer->getIdSalesOrderThresholdType()
            )->save();

        return $this->getFactory()
            ->createSalesOrderThresholdMapper()
            ->mapSalesOrderThresholdEntityToTransfer(
                $salesOrderThresholdEntity,
                $salesOrderThresholdTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return bool
     */
    public function deleteSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): bool {
        $salesOrderThresholdEntity = $this->getFactory()
            ->createSalesOrderThresholdQuery()
            ->findOneByIdSalesOrderThreshold(
                $salesOrderThresholdTransfer->getIdSalesOrderThreshold()
            );

        if ($salesOrderThresholdEntity) {
            $salesOrderThresholdEntity->delete();

            return $salesOrderThresholdEntity->isDeleted();
        }

        return false;
    }

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveSalesOrderThresholdTaxSet(int $idTaxSet): void
    {
        $this->getFactory()
            ->createSalesOrderThresholdTaxSetPropelQuery()
            ->findOneOrCreate()
            ->setFkTaxSet($idTaxSet)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): void
    {
        $salesOrderThresholdTransfer
            ->requireSalesOrderThresholdValue()
            ->requireStore()
            ->requireCurrency();

        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->requireThreshold()
            ->requireMessageGlossaryKey()
            ->requireSalesOrderThresholdType();

        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()
            ->requireIdSalesOrderThresholdType()
            ->requireThresholdGroup();

        $salesOrderThresholdTransfer->getStore()
            ->requireIdStore();

        $salesOrderThresholdTransfer->getCurrency()
            ->requireIdCurrency();
    }
}
