<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\ProductAlternativeDataSet\ProductAlternativeDataSetInterface;

class AlternativeProductConcreteSkuToProductIdStep implements DataImportStepInterface
{
    /**
     * @var array<int>
     */
    protected $idProductConcreteCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]) {
            return;
        }

        $this->addAlternativeProductConcreteId($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function addAlternativeProductConcreteId(DataSetInterface $dataSet): void
    {
        $productConcreteSku = $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU];

        if (!isset($this->idProductConcreteCache[$productConcreteSku])) {
            /** @var \Orm\Zed\Product\Persistence\SpyProduct|null $productConcreteEntity */
            $productConcreteEntity = SpyProductQuery::create()->findOneBySku($productConcreteSku);
            if (!$productConcreteEntity) {
                throw new EntityNotFoundException(
                    sprintf(
                        'Could not find product by sku "%s"',
                        $productConcreteSku,
                    ),
                );
            }

            $this->idProductConcreteCache[$productConcreteSku] = $productConcreteEntity->getIdProduct();
        }
        $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_CONCRETE_ALTERNATIVE] = $this->idProductConcreteCache[$productConcreteSku];
    }
}
