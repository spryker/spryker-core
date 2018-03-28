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
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    public function getProductConcreteMeasurementUnitStorageByIdProduct(int $idProduct): ProductConcreteMeasurementUnitStorageTransfer
    {
        $productMeasurementBaseUnitEntity = $this->productMeasurementUnitFacade->getBaseUnitByIdProduct($idProduct);
        $productMeasurementSalesUnitEntities = $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct($idProduct);

        $productConcreteMeasurementUnitStorageTransfer = (new ProductConcreteMeasurementUnitStorageTransfer())
            ->setBaseUnit(
                (new ProductConcreteMeasurementBaseUnitTransfer())
                    ->setIdProductMeasurementUnit($productMeasurementBaseUnitEntity->getFkProductMeasurementUnit())
            )
            ->setSalesUnits(new ArrayObject());

        foreach ($productMeasurementSalesUnitEntities as $productMeasurementSalesUnitEntity) {
            $productConcreteMeasurementUnitStorageTransfer->addSalesUnit(
                (new ProductConcreteMeasurementSalesUnitTransfer())
                    ->fromArray($productMeasurementSalesUnitEntity->toArray(), true)
                    ->setIdProductMeasurementUnit($productMeasurementSalesUnitEntity->getFkProductMeasurementUnit())
            );
        }

        return $productConcreteMeasurementUnitStorageTransfer;
    }
}
