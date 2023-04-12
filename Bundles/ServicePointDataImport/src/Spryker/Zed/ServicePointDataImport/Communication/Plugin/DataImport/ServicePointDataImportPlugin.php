<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ServicePointDataImport\ServicePointDataImportConfig;

/**
 * @method \Spryker\Zed\ServicePointDataImport\Business\ServicePointDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointDataImport\ServicePointDataImportConfig getConfig()
 * @method \Spryker\Zed\ServicePointDataImport\Communication\ServicePointDataImportCommunicationFactory getFactory()
 */
class ServicePointDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports service points data from the specified file.
     * - Iterates over the data sets and stores the data at Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importServicePoints($dataImporterConfigurationTransfer);
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
        return ServicePointDataImportConfig::IMPORT_TYPE_SERVICE_POINT;
    }
}
