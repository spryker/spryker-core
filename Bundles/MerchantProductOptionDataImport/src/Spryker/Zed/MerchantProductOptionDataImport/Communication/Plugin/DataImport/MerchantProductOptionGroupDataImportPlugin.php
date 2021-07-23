<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOptionDataImport\MerchantProductOptionDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantProductOptionDataImport\MerchantProductOptionDataImportConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionDataImport\Business\MerchantProductOptionDataImportFacadeInterface getFacade()
 */
class MerchantProductOptionGroupDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates Merchant reference.
     * - Inserts merchant product option groups into DB.
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
        return $this->getFacade()->importMerchantProductOptionGroupData($dataImporterConfigurationTransfer);
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
        return MerchantProductOptionDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OPTION_GROUP;
    }
}
