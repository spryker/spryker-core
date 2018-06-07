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
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[] Keys are store names
     */
    public function generateProductConcreteMeasurementUnitStorageTransfersByIdProduct(int $idProduct): array
    {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct($idProduct);

        $relatedStoreNames = $this->extractRelatedStoreNames($productMeasurementSalesUnitTransfers);
        $productConcreteMeasurementBaseUnitTransfer = $this->extractProductConcreteMeasurementBaseUnitTransfer($productMeasurementSalesUnitTransfers);
        $mappedProductConcreteMeasurementSalesUnitTransfers = $this->extractProductConcreteMeasurementSalesUnitTransfersByStoreName($productMeasurementSalesUnitTransfers);

        $productConcreteMeasurementUnitStorageTransfers = [];
        foreach ($relatedStoreNames as $storeName) {
            $productConcreteMeasurementUnitStorageTransfer = (new ProductConcreteMeasurementUnitStorageTransfer())
                ->setBaseUnit($productConcreteMeasurementBaseUnitTransfer)
                ->setSalesUnits(new ArrayObject($mappedProductConcreteMeasurementSalesUnitTransfers[$storeName]));

            $productConcreteMeasurementUnitStorageTransfers[$storeName] = $productConcreteMeasurementUnitStorageTransfer;
        }

        return $productConcreteMeasurementUnitStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return array First level keys are store names, second level keys are numeric, values are ProductConcreteMeasurementSalesUnitTransfers
     */
    protected function extractProductConcreteMeasurementSalesUnitTransfersByStoreName(array $productMeasurementSalesUnitTransfers): array
    {
        $mappedProductConcreteMeasurementSalesUnitTransfers = [];
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            foreach ($productMeasurementSalesUnitTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                $mappedProductConcreteMeasurementSalesUnitTransfers[$storeTransfer->getName()][] = (new ProductConcreteMeasurementSalesUnitTransfer())
                    ->fromArray($productMeasurementSalesUnitTransfer->toArray(), true)
                    ->setIdProductMeasurementUnit($productMeasurementSalesUnitTransfer->getFkProductMeasurementUnit());
            }
        }

        return $mappedProductConcreteMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return null|\Generated\Shared\Transfer\ProductConcreteMeasurementBaseUnitTransfer
     */
    protected function extractProductConcreteMeasurementBaseUnitTransfer(array $productMeasurementSalesUnitTransfers): ?ProductConcreteMeasurementBaseUnitTransfer
    {
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            $productConcreteMeasurementBaseUnitTransfer = (new ProductConcreteMeasurementBaseUnitTransfer())
                ->fromArray($productMeasurementSalesUnitTransfer->getProductMeasurementBaseUnit()->toArray(), true)
                ->setIdProductMeasurementUnit($productMeasurementSalesUnitTransfer->getProductMeasurementBaseUnit()->getFkProductMeasurementUnit());

            return $productConcreteMeasurementBaseUnitTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return string[]
     */
    protected function extractRelatedStoreNames(array $productMeasurementSalesUnitTransfers): array
    {
        $relatedStores = [];
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            foreach ($productMeasurementSalesUnitTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                if (in_array($storeTransfer->getName(), $relatedStores, true)) {
                    continue;
                }

                $relatedStores[] = $storeTransfer->getName();
            }
        }

        return $relatedStores;
    }
}
