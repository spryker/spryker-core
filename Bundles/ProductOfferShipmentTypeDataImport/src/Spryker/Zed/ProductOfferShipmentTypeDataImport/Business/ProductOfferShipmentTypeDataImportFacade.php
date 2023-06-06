<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\ProductOfferShipmentTypeDataImportBusinessFactory getFactory()
 */
class ProductOfferShipmentTypeDataImportFacade extends AbstractFacade implements ProductOfferShipmentTypeDataImportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importProductOfferShipmentTypes(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getProductOfferShipmentTypeDataImporter()
            ->import($dataImporterConfigurationTransfer);
    }
}
