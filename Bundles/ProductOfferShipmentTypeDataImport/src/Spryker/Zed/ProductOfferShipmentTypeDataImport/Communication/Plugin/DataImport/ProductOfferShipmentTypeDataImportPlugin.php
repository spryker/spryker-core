<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportConfig;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\ProductOfferShipmentTypeDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeDataImport\Communication\ProductOfferShipmentTypeDataImportCommunicationFactory getFactory()
 */
class ProductOfferShipmentTypeDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports product offer shipment types data from the specified file.
     * - Iterates over the data sets and stores the data at Persistence.
     * - Performs create only. Does not update records.
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
        return $this->getFacade()->importProductOfferShipmentTypes($dataImporterConfigurationTransfer);
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
        return ProductOfferShipmentTypeDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_SHIPMENT_TYPE;
    }
}
