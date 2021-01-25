<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig;

/**
 * @method \Spryker\Zed\ProductRelationDataImport\Business\ProductRelationDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig getConfig()
 */
class ProductRelationDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports product relations data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importProductRelations($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns the name of the product relations DataImporter.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ProductRelationDataImportConfig::IMPORT_TYPE_PRODUCT_RELATION;
    }
}
