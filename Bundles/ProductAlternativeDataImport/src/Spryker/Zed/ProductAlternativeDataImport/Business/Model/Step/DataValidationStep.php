<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Exception\NoAlternativesException;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet\ProductAlternativeDataSetInterface;

class DataValidationStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\ProductAlternativeDataImport\Business\Exception\NoAlternativesException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (!$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU] &&
            !$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]
        ) {
            throw new NoAlternativesException(
                sprintf(
                    'Product concrete with "%s" SKU has neither concrete nor abstract alternative',
                    $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_CONCRETE_SKU]
                )
            );
        }
    }
}
