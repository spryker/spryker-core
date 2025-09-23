<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class AssertAssignedProductTypeStep implements DataImportStepInterface
{
    /**
     * @var array<string>
     */
    public const ASSIGNABLE_PRODUCT_TYPES = [
        MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
        MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
        MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE])) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('"%s%" must be defined in the data set.')
                    ->setParameters(['%s%' => MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE]),
            );
        }

        if (!in_array($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE], static::ASSIGNABLE_PRODUCT_TYPES, true)) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('"%s1%" must have one of the following values: "%s2%". Given: "%s3%"')
                    ->setParameters([
                        '%s1%' => MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE,
                        '%s2%' => implode(', ', static::ASSIGNABLE_PRODUCT_TYPES),
                        '%s3%' => $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE],
                    ]),
            );
        }
    }
}
