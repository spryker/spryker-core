<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductDataImport\Business\Model\DataSet\PriceProductDataSet;

class ConcreteSkuToIdProductStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idProductCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productConcreteSku = $dataSet[PriceProductDataSet::KEY_CONCRETE_SKU];
        if (empty($productConcreteSku)) {
            return;
        }

        if (!isset($this->idProductCache[$productConcreteSku])) {
            $productQuery = new SpyProductQuery();
            $idProduct = $productQuery
                ->select(SpyProductTableMap::COL_ID_PRODUCT)
                ->findOneBySku($productConcreteSku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find product by sku "%s"', $productConcreteSku));
            }

            $this->idProductCache[$productConcreteSku] = $idProduct;
        }

        $dataSet[PriceProductDataSet::ID_PRODUCT_CONCRETE] = $this->idProductCache[$productConcreteSku];
    }
}
