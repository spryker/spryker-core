<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferServicePointDataImport\ProductOfferServicePointDataImportConfig;

/**
 * @method \Spryker\Zed\ProductOfferServicePointDataImport\Business\ProductOfferServicePointDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferServicePointDataImport\ProductOfferServicePointDataImportConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePointDataImport\Communication\ProductOfferServicePointDataImportCommunicationFactory getFactory()
 */
class ProductOfferServiceDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports product offer service data from the specified file.
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
        return $this->getFacade()->importProductOfferServices($dataImporterConfigurationTransfer);
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
        return ProductOfferServicePointDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_SERVICE;
    }
}
