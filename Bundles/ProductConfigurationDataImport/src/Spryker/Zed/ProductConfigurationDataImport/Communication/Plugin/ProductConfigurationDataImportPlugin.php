<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductConfigurationDataImport\ProductConfigurationDataImportConfig;

/**
 * @method \Spryker\Zed\ProductConfigurationDataImport\Business\ProductConfigurationDataImportFacade getFacade()
 * @method \Spryker\Zed\ProductConfigurationDataImport\ProductConfigurationDataImportConfig getConfig()
 */
class ProductConfigurationDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports product configuration data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        return $this->getFacade()->import($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getImportType()
    {
        return ProductConfigurationDataImportConfig::IMPORT_TYPE_PRODUCT_CONFIGURATION;
    }
}
