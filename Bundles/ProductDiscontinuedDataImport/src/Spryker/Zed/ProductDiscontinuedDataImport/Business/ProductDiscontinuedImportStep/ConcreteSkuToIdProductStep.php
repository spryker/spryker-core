<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedImportStep;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedDataSet\ProductDiscontinuedDataSetInterface;

class ConcreteSkuToIdProductStep implements DataImportStepInterface
{
    /**
     * @var int[]
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
        $concreteSku = $dataSet[ProductDiscontinuedDataSetInterface::KEY_CONCRETE_SKU];
        if (!isset($this->idProductCache[$concreteSku])) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
            $productQuery = SpyProductQuery::create()
                ->select(SpyProductTableMap::COL_ID_PRODUCT);
            /** @var string|int|null $idProduct */
            $idProduct = $productQuery
                ->findOneBySku($concreteSku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find product by sku %s', $concreteSku));
            }

            $this->idProductCache[$concreteSku] = (int)$idProduct;
        }

        $dataSet[ProductDiscontinuedDataSetInterface::ID_PRODUCT] = $this->idProductCache[$concreteSku];
    }
}
