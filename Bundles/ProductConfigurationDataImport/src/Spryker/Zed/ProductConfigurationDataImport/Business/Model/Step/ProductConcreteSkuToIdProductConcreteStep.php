<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\DataSet\ProductConfigurationDataSet;

class ProductConcreteSkuToIdProductConcreteStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $productConcreteIdsCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productConcreteSku = $dataSet[ProductConfigurationDataSet::KEY_CONCRETE_SKU];

        if (empty($productConcreteSku)) {
            throw new DataKeyNotFoundInDataSetException(
                sprintf(
                    '"%s" key must be in the data set. Given: "%s"',
                    ProductConfigurationDataSet::KEY_CONCRETE_SKU,
                    implode(', ', array_keys($dataSet->getArrayCopy()))
                )
            );
        }

        if (!isset($this->productConcreteIdsCache[$productConcreteSku])) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
            $productQuery = SpyProductQuery::create()
                ->select(SpyProductTableMap::COL_ID_PRODUCT);
            /** @var int|null $idProductConcrete */
            $idProductConcrete = $productQuery
                ->findOneBySku($productConcreteSku);

            if (!$idProductConcrete) {
                throw new EntityNotFoundException(
                    sprintf('Could not find product concrete by sku "%s"', $productConcreteSku)
                );
            }

            $this->productConcreteIdsCache[$productConcreteSku] = $idProductConcrete;
        }

        $dataSet[ProductConfigurationDataSet::ID_PRODUCT_CONCRETE] = $this->productConcreteIdsCache[$productConcreteSku];
    }
}
