<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductApprovalDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductApprovalDataImport\ProductApprovalDataImportConfig;

/**
 * @method \Spryker\Zed\ProductApprovalDataImport\Business\ProductApprovalDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductApprovalDataImport\ProductApprovalDataImportConfig getConfig()
 */
class ProductAbstractApprovalStatusDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports abstract product approval statuses.
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
        return $this->getFacade()->importProductAbstractApprovalStatus($dataImporterConfigurationTransfer);
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
        return ProductApprovalDataImportConfig::IMPORT_TYPE_PRODUCT_APPROVAL_STATUS;
    }
}
