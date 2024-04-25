<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantCommissionDataImport\Business\MerchantCommissionDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig getConfig()
 * @method \Spryker\Zed\MerchantCommissionDataImport\Communication\MerchantCommissionDataImportCommunicationFactory getFactory()
 */
class MerchantCommissionStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports merchant commission store data from the specified file.
     * - Iterates over the data sets and stores the data at Persistence.
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
        return $this->getFacade()->importMerchantCommissionStore($dataImporterConfigurationTransfer);
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
        return MerchantCommissionDataImportConfig::IMPORT_TYPE_MERCHANT_COMMISSION_STORE;
    }
}
