<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Step;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\DataSet\ProductAlternativeDataSetInterface;

class DataValidationStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->checkIfProductHasConcreteOrAbstractAlternative($dataSet);
        $this->checkIfProductHasConcreteAndAbstractAlternative($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function checkIfProductHasConcreteOrAbstractAlternative(DataSetInterface $dataSet): void
    {
        if (!$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU] &&
            !$dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]
        ) {
            throw new InvalidDataException(
                sprintf(
                    'Product concrete with SKU "%s" has neither concrete nor abstract alternative products',
                    $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_CONCRETE_SKU]
                )
            );
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function checkIfProductHasConcreteAndAbstractAlternative(DataSetInterface $dataSet): void
    {
        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU] &&
            $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]
        ) {
            throw new InvalidDataException(
                sprintf(
                    'Product concrete with SKU "%s" has both a concrete and an abstract alternative products',
                    $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_CONCRETE_SKU]
                )
            );
        }
    }
}
