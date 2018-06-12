<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet\ProductAlternativeDataSetInterface;

class ProductAlternativeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * *
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productAlternativeEntity = SpyProductAlternativeQuery::create()
            ->filterByFkProduct($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_PRODUCT_ID]);

        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]) {
            $productAlternativeEntity = $productAlternativeEntity->filterByFkProductConcreteAlternative(
                $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_ID]
            )->findOneOrCreate();
            $productConcreteAlternative = SpyProductQuery::create()->findOneBySku(
                $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]
            );

            $productAlternativeEntity->setProductConcreteAlternative($productConcreteAlternative);
        }

        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            $productAlternativeEntity = $productAlternativeEntity->filterByFkProductAbstractAlternative(
                $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_ID]
            )->findOneOrCreate();

            $productAbstractAlternative = SpyProductAbstractQuery::create()->findOneBySku(
                $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]
            );

            $productAlternativeEntity->setProductAbstractAlternative($productAbstractAlternative);
        }

        $productAlternativeEntity->save();
    }
}
