<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportConfig;

/**
 * @method \Spryker\Zed\ShipmentTypeDataImport\Business\ShipmentTypeDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeDataImport\Communication\ShipmentTypeDataImportCommunicationFactory getFactory()
 */
class ShipmentTypeDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports shipment types data from the specified file.
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
        return $this->getFacade()->importShipmentTypes($dataImporterConfigurationTransfer);
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
        return ShipmentTypeDataImportConfig::IMPORT_TYPE_SHIPMENT_TYPE;
    }
}
