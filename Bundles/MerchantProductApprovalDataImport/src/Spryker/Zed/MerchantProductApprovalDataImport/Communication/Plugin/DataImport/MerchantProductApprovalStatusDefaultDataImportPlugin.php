<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductApprovalDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductApprovalDataImport\MerchantProductApprovalDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantProductApprovalDataImport\Business\MerchantProductApprovalDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductApprovalDataImport\MerchantProductApprovalDataImportConfig getConfig()
 */
class MerchantProductApprovalStatusDefaultDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
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
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()
            ->importMerchantProductApprovalStatusDefault($dataImporterConfigurationTransfer);
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
        return MerchantProductApprovalDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_APPROVAL_STATUS_DEFAULT;
    }
}
