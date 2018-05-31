<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductDataImport\Business\Model\DataSet\PriceProductDataSet;

class AbstractSkuToIdAbstractProductStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idProductAbstractCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productAbstractSku = $dataSet[PriceProductDataSet::KEY_ABSTRACT_SKU];
        if (empty($productAbstractSku)) {
            return;
        }

        if (!isset($this->idProductAbstractCache[$productAbstractSku])) {
            $productQuery = new SpyProductAbstractQuery();
            $idProduct = $productQuery
                ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
                ->findOneBySku($productAbstractSku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find abstract product by sku "%s"', $productAbstractSku));
            }

            $this->idProductAbstractCache[$productAbstractSku] = $idProduct;
        }

        $dataSet[PriceProductDataSet::ID_PRODUCT_ABSTRACT] = $this->idProductAbstractCache[$productAbstractSku];
    }
}
