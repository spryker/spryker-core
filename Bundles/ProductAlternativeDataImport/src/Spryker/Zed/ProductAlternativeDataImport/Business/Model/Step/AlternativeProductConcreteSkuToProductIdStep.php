<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet\ProductAlternativeDataSetInterface;

class AlternativeProductConcreteSkuToProductIdStep implements DataImportStepInterface
{
    /**
     * @var array
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
        if (!$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]) {
            return;
        }

        $productAlternativeSku = $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU];

        if (!isset($this->idProductAlternativeCache[$productAlternativeSku])) {
            $idProductAlternative = SpyProductQuery::create()->findOneBySku($productAlternativeSku)->getIdProduct();

            if (!$idProductAlternative) {
                throw new EntityNotFoundException(sprintf(
                    'Could not find concrete product by sku "%s"',
                    $productAlternativeSku
                ));
            }

            $this->idProductAlternativeCache[$productAlternativeSku] = $idProductAlternative;
        }
        $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_ID] =
            $this->idProductAlternativeCache[$productAlternativeSku];
    }
}
