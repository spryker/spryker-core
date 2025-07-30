<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class AssertRequiredProductAbstractDataStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        if (!$this->getProductAbstractSku($dataSet)) {
            throw new MerchantCombinedProductException(sprintf(
                'SKU is required for product abstract import. "%s" is missing in the data set.',
                MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU,
            ));
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string|null
     */
    protected function getProductAbstractSku(DataSetInterface $dataSet): ?string
    {
        return $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU] ?? null;
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }
}
