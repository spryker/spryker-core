<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\DataSet\ProductConfigurationDataSet;

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
        $productConcreteSku = $dataSet[ProductConfigurationDataSet::KEY_CONCRETE_SKU];
        if (empty($productConcreteSku)) {
            return;
        }

        if (!isset($this->idProductCache[$productConcreteSku])) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
            $productQuery = SpyProductQuery::create()
                ->select(SpyProductTableMap::COL_ID_PRODUCT);
            /** @var string|int|null $idProduct */
            $idProduct = $productQuery
                ->findOneBySku($productConcreteSku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find product by sku "%s"', $productConcreteSku));
            }

            $this->idProductCache[$productConcreteSku] = $idProduct;
        }

        $dataSet[ProductConfigurationDataSet::ID_PRODUCT_CONCRETE] = $this->idProductCache[$productConcreteSku];
    }
}
