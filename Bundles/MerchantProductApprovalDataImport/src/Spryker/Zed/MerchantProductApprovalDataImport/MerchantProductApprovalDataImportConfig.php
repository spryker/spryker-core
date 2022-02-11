<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductApprovalDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class MerchantProductApprovalDataImportConfig extends DataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_PRODUCT_APPROVAL_STATUS_DEFAULT = 'merchant-product-approval-status-default';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantProductApprovalStatusDefaultDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $importFile = $this->getModuleDataImportDirectory('merchant_product_approval_status_default.csv');

        return $this->buildImporterConfiguration($importFile, static::IMPORT_TYPE_MERCHANT_PRODUCT_APPROVAL_STATUS_DEFAULT);
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        return (string)realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..',
        );
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getModuleDataImportDirectory(string $fileName): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getModuleRoot(),
            'data',
            'import',
            $fileName,
        ]);
    }
}
