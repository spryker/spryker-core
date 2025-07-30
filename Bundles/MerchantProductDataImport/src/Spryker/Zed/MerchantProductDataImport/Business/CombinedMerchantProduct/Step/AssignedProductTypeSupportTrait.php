<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

trait AssignedProductTypeSupportTrait
{
    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isAssignedProductTypeSupported(DataSetInterface $dataSet): bool
    {
        return in_array(
            $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE],
            $this->getSupportedAssignedProductTypes(),
            true,
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isProductAbstractSupported(DataSetInterface $dataSet): bool
    {
        return in_array(
            $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE],
            [
                MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
                MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
            ],
            true,
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isProductConcreteSupported(DataSetInterface $dataSet): bool
    {
        return in_array(
            $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE],
            [
                MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
                MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
            ],
            true,
        );
    }
}
