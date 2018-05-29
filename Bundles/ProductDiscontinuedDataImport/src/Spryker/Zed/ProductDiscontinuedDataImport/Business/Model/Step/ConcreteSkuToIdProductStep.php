<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\Model\DataSet\ProductDiscontinuedDataSet;

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
    public function execute(DataSetInterface $dataSet)
    {
        $concreteSku = $dataSet[ProductDiscontinuedDataSet::KEY_CONCRETE_SKU];
        if (!isset($this->idProductCache[$concreteSku])) {
            $productQuery = SpyProductQuery::create();
            $idProduct = $productQuery
                ->select(SpyProductTableMap::COL_ID_PRODUCT)
                ->findOneBySku($concreteSku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find product by sku %s', $concreteSku));
            }

            $this->idProductCache[$concreteSku] = $idProduct;
        }

        $dataSet[ProductDiscontinuedDataSet::ID_PRODUCT] = $this->idProductCache[$concreteSku];
    }
}
