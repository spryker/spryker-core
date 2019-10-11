<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentDataImport\ShipmentDataImportConfig;

/**
 * @method \Spryker\Zed\ShipmentDataImport\Business\ShipmentDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentDataImport\ShipmentDataImportConfig getConfig()
 */
class ShipmentStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports shipment method store data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importShipmentMethodStore($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns the name of shipment store DataImporter.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ShipmentDataImportConfig::IMPORT_TYPE_SHIPMENT_STORE;
    }
}
