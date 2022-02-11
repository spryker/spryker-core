<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductApprovalDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductApprovalDataImportConfig extends DataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_APPROVAL_STATUS = 'product-approval-status';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductAbstractApprovalStatusDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $importFile = $this->getModuleDataImportDirectory('product_abstract_approval_status.csv');

        return $this->buildImporterConfiguration($importFile, static::IMPORT_TYPE_PRODUCT_APPROVAL_STATUS);
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
