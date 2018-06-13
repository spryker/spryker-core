<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet\ProductAlternativeDataSetInterface;

class AlternativeProductAbstractSkuToProductIdStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idProductAlternativeCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (!$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            return;
        }

        $productAlternativeSku = $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU];

        if (!isset($this->idProductAlternativeCache[$productAlternativeSku])) {
            $productAlternativeEntity = SpyProductAbstractQuery::create()->findOneBySku($productAlternativeSku);

            if (!$productAlternativeEntity) {
                throw new EntityNotFoundException(sprintf(
                    'Could not find product by sku "%s"',
                    $productAlternativeSku
                ));
            }

            $this->idProductAlternativeCache[$productAlternativeSku] = $productAlternativeEntity->getIdProductAbstract();
        }
        $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_ABSTRACT_ALTERNATIVE] =
            $this->idProductAlternativeCache[$productAlternativeSku];
    }
}
