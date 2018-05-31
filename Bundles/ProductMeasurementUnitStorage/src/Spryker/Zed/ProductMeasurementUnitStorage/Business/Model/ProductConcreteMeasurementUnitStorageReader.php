<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface;

class ProductConcreteMeasurementUnitStorageReader implements ProductConcreteMeasurementUnitStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade)
    {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[]
     */
    public function getProductConcreteMeasurementUnitStorageByIdProduct(int $idProduct): array
    {
        $unitsStoreCollection = [];
        $salesUnitStores = $this->getProductConcreteMeasurementSalesUnitTransfers($idProduct);
        $productMeasurementBaseUnitEntity = $this->productMeasurementUnitFacade->getBaseUnitByIdProduct($idProduct);
        $baseUnit = (new ProductConcreteMeasurementBaseUnitTransfer())
            ->fromArray($productMeasurementBaseUnitEntity->toArray(), true)
            ->setIdProductMeasurementUnit($productMeasurementBaseUnitEntity->getFkProductMeasurementUnit());

        foreach ($salesUnitStores as $store => $salesUnitStore) {
            $productConcreteMeasurementUnitStorageTransfer = (new ProductConcreteMeasurementUnitStorageTransfer())
                ->setBaseUnit($baseUnit)
                ->setSalesUnits(new ArrayObject($salesUnitStore));
            $unitsStoreCollection[$store] = $productConcreteMeasurementUnitStorageTransfer;
        }

        return $unitsStoreCollection;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer[]
     */
    protected function getProductConcreteMeasurementSalesUnitTransfers(int $idProduct): array
    {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct($idProduct);
        $productMeasurementSalesUnitEntitiesStorePair = [];
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            foreach ($productMeasurementSalesUnitTransfer->getStores() as $productMeasurementSalesUnitStoreTransfer) {
                $productMeasurementSalesUnitEntitiesStorePair[$productMeasurementSalesUnitStoreTransfer->getStore()->getName()][] = (new ProductConcreteMeasurementSalesUnitTransfer())
                    ->fromArray($productMeasurementSalesUnitTransfer->toArray(), true)
                    ->setIdProductMeasurementUnit($productMeasurementSalesUnitTransfer->getFkProductMeasurementUnit());
            }
        }

        return $productMeasurementSalesUnitEntitiesStorePair;
    }
}
