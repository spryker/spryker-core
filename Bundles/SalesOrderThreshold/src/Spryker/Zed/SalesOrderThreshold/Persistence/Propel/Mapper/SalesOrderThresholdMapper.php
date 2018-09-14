<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType;

class SalesOrderThresholdMapper implements SalesOrderThresholdMapperInterface
{
    /**
     * @param \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType $spySalesOrderThresholdType
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function mapSalesOrderThresholdTypeEntityToTransfer(
        SpySalesOrderThresholdType $spySalesOrderThresholdType,
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer {
        $salesOrderThresholdTypeTransfer
            ->fromArray($spySalesOrderThresholdType->toArray(), true)
            ->setIdSalesOrderThresholdType($spySalesOrderThresholdType->getIdSalesOrderThresholdType());

        return $salesOrderThresholdTypeTransfer;
    }

    /**
     * @param \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold $salesOrderThresholdEntity
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function mapSalesOrderThresholdEntityToTransfer(
        SpySalesOrderThreshold $salesOrderThresholdEntity,
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer {
        $salesOrderThresholdTransfer->fromArray($salesOrderThresholdEntity->toArray(), true)
            ->setIdSalesOrderThreshold($salesOrderThresholdEntity->getIdSalesOrderThreshold())
            ->setSalesOrderThresholdValue(
                $this->mapSalesOrderThresholdValueTransfer($salesOrderThresholdTransfer, $salesOrderThresholdEntity)
            )->setCurrency(
                (new CurrencyTransfer())->fromArray($salesOrderThresholdEntity->getCurrency()->toArray(), true)
            )->setStore(
                (new StoreTransfer())->fromArray($salesOrderThresholdEntity->getStore()->toArray(), true)
            );

        if (!$salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()) {
            $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->setSalesOrderThresholdType(new SalesOrderThresholdTypeTransfer());
        }

        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->setSalesOrderThresholdType(
            $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->fromArray(
                $salesOrderThresholdEntity->getSalesOrderThresholdType()->toArray(),
                true
            )
        );

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold $salesOrderThresholdEntity
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer
     */
    protected function mapSalesOrderThresholdValueTransfer(SalesOrderThresholdTransfer $salesOrderThresholdTransfer, SpySalesOrderThreshold $salesOrderThresholdEntity): SalesOrderThresholdValueTransfer
    {
        $salesOrderThresholdValueTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue() ?? (new SalesOrderThresholdValueTransfer());
        $salesOrderThresholdValueTransfer = $salesOrderThresholdValueTransfer
            ->fromArray($salesOrderThresholdEntity->toArray(), true)
            ->setFee($salesOrderThresholdEntity->getFee())
            ->setThreshold($salesOrderThresholdEntity->getThreshold())
            ->setMessageGlossaryKey($salesOrderThresholdEntity->getMessageGlossaryKey());

        return $salesOrderThresholdValueTransfer;
    }
}
