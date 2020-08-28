<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportConfig getConfig()
 */
class ConfigurableBundleTemplateDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports Configurable Bundle Templates data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()
            ->importConfigurableBundleTemplates($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE;
    }
}
