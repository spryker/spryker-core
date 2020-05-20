<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderItemMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntities
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function mapSalesOrderItemEntitiesToProductMeasurementSalesUnitTransfers(ObjectCollection $salesOrderItemEntities): array
    {
        $mappedProductMeasurementSalesUnitTransfers = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $productMeasurementSalesUnitTransfer = $this->mapSalesOrderItemEntityToProductMeasurementSalesUnitTransfer(
                $salesOrderItemEntity,
                new ProductMeasurementSalesUnitTransfer()
            );

            $mappedProductMeasurementSalesUnitTransfers[$salesOrderItemEntity->getIdSalesOrderItem()] = $productMeasurementSalesUnitTransfer;
        }

        return $mappedProductMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function mapSalesOrderItemEntityToProductMeasurementSalesUnitTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $productMeasurementSalesUnitTransfer = $productMeasurementSalesUnitTransfer
            ->setConversion($salesOrderItemEntity->getQuantityMeasurementUnitConversion())
            ->setPrecision($salesOrderItemEntity->getQuantityMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($salesOrderItemEntity);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer(
            $salesOrderItemEntity->getQuantityMeasurementUnitName(),
            $salesOrderItemEntity->getQuantityMeasurementUnitCode()
        );
        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    protected function createProductMeasurementBaseUnitTransfer(SpySalesOrderItem $salesOrderItemEntity): ProductMeasurementBaseUnitTransfer
    {
        $productMeasurementBaseUnitTransfer = new ProductMeasurementBaseUnitTransfer();

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($salesOrderItemEntity->getQuantityBaseMeasurementUnitName());
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param string|null $productMeasurementUnitName
     * @param string|null $productMeasurementUnitCode
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function createProductMeasurementUnitTransfer(
        ?string $productMeasurementUnitName,
        ?string $productMeasurementUnitCode = null
    ): ProductMeasurementUnitTransfer {
        $productMeasurementUnitTransfer = new ProductMeasurementUnitTransfer();
        $productMeasurementUnitTransfer->setName($productMeasurementUnitName ?: '');

        if ($productMeasurementUnitCode !== null) {
            $productMeasurementUnitTransfer->setCode($productMeasurementUnitCode);
        }

        return $productMeasurementUnitTransfer;
    }
}
