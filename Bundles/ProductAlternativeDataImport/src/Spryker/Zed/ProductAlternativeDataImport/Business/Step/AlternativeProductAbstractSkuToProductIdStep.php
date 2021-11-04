<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Step;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\ProductAlternativeDataSet\ProductAlternativeDataSetInterface;

class AlternativeProductAbstractSkuToProductIdStep implements DataImportStepInterface
{
    /**
     * @var array<int>
     */
    protected $idProductAbstractCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            return;
        }

        $this->addAlternativeProductAbstractId($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function addAlternativeProductAbstractId(DataSetInterface $dataSet): void
    {
        $productAlternativeSku = $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU];

        if (!isset($this->idProductAbstractCache[$productAlternativeSku])) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract|null $productAbstractEntity */
            $productAbstractEntity = SpyProductAbstractQuery::create()->findOneBySku($productAlternativeSku);
            if (!$productAbstractEntity) {
                throw new EntityNotFoundException(
                    sprintf(
                        'Could not find product by sku "%s"',
                        $productAlternativeSku,
                    ),
                );
            }

            $this->idProductAbstractCache[$productAlternativeSku] = $productAbstractEntity->getIdProductAbstract();
        }
        $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_ABSTRACT_ALTERNATIVE] = $this->idProductAbstractCache[$productAlternativeSku];
    }
}
