<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 */
class MerchantCombinedProductOfferDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * - Imports combined merchant product offer data.
     *
     * @api
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importMerchantCombinedMerchantProductOffer($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getImportType(): string
    {
        return MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER;
    }
}
