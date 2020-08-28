<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductLabelDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabelDataImport\ProductLabelDataImportConfig;

/**
 * @method \Spryker\Zed\ProductLabelDataImport\ProductLabelDataImportConfig getConfig()
 * @method \Spryker\Zed\ProductLabelDataImport\Business\ProductLabelDataImportFacadeInterface getFacade()
 */
class ProductLabelStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes product label store data importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importProductLabelStore($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns name of the product label store import type.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ProductLabelDataImportConfig::IMPORT_TYPE_PRODUCT_LABEL_STORE;
    }
}
