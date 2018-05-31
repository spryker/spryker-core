<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitStoreTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit;

class ProductMeasurementUnitMapper implements ProductMeasurementUnitMapperInterface
{
    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function mapProductMeasurementSalesUnitTransfer(
        SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $productMeasurementSalesUnitTransfer->fromArray($productMeasurementSalesUnitEntity->toArray(), true);

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())->fromArray($productMeasurementSalesUnitEntity->getProductMeasurementUnit()->toArray(), true)
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit(
            $this->mapProductMeasurementBaseUnitTransfer(
                $productMeasurementSalesUnitEntity->getProductMeasurementBaseUnit(),
                new ProductMeasurementBaseUnitTransfer()
            )
        );

        return $this->hydrateStoresToProductMeasurementSalesUnitTransfer($productMeasurementSalesUnitEntity, $productMeasurementSalesUnitTransfer);
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit $productMeasurementBaseUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function mapProductMeasurementBaseUnitTransfer(
        SpyProductMeasurementBaseUnit $productMeasurementBaseUnitEntity,
        ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
    ): ProductMeasurementBaseUnitTransfer {
        $productMeasurementBaseUnitTransfer->fromArray($productMeasurementBaseUnitEntity->toArray(), true);

        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer)->fromArray($productMeasurementBaseUnitEntity->getProductMeasurementUnit()->toArray(), true)
        );

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit $productMeasurementUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    public function mapProductMeasurementUnitTransfer(
        SpyProductMeasurementUnit $productMeasurementUnitEntity,
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): ProductMeasurementUnitTransfer {
        $productMeasurementUnitTransfer->fromArray($productMeasurementUnitEntity->toArray(), true);

        return $productMeasurementUnitTransfer;
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function hydrateStoresToProductMeasurementSalesUnitTransfer(
        SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $spyProductMeasurementSalesUnitStores = $productMeasurementSalesUnitEntity->getSpyProductMeasurementSalesUnitStores();

        $productMeasurementSalesUnitStoreTransfers = new ArrayObject();
        foreach ($spyProductMeasurementSalesUnitStores as $spyProductMeasurementSalesUnitStore) {
            $productMeasurementSalesUnitStoreTransfer = (new ProductMeasurementSalesUnitStoreTransfer())->fromArray(
                $spyProductMeasurementSalesUnitStore->toArray(),
                true
            );

            $productMeasurementSalesUnitStoreTransfer->setStore(
                (new StoreTransfer())->fromArray($spyProductMeasurementSalesUnitStore->getSpyStore()->toArray(), true)
            );
            $productMeasurementSalesUnitStoreTransfers->append($productMeasurementSalesUnitStoreTransfer);
        }

        $productMeasurementSalesUnitTransfer->setStores($productMeasurementSalesUnitStoreTransfers);

        return $productMeasurementSalesUnitTransfer;
    }
}
