<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model;

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
            ->filterByFkProduct($dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT]);

        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]) {
            $productAlternativeEntity = $productAlternativeEntity->filterByFkProductConcreteAlternative(
                $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_CONCRETE_ALTERNATIVE]
            )->findOneOrCreate()->setFkProductConcreteAlternative(
                $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]
            );
        }

        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            $productAlternativeEntity = $productAlternativeEntity->filterByFkProductAbstractAlternative(
                $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_ABSTRACT_ALTERNATIVE]
            )->findOneOrCreate()->setFkProductAbstractAlternative(
                $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]
            );
        }

        $productAlternativeEntity->save();
    }
}
