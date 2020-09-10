<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCategoryDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantCategoryDataImport\MerchantCategoryDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantCategoryDataImport\Business\MerchantCategoryDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCategoryDataImport\MerchantCategoryDataImportConfig getConfig()
 */
class MerchantCategoryDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports data for relations between merchants and product abstracts.
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
        return $this->getFacade()->importMerchantCategory($dataImporterConfigurationTransfer);
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
        return MerchantCategoryDataImportConfig::IMPORT_TYPE_MERCHANT_CATEGORY;
    }
}
