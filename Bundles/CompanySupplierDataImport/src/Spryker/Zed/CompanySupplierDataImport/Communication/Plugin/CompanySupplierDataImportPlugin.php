<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanySupplierDataImport\Business\CompanySupplierDataImportFacadeInterface getFacade()
 */
class CompanySupplierDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        return $this->getFacade()->importCompanySupplier($dataImporterConfigurationTransfer);
    }

    /**
     * @return string
     */
    public function getImportType()
    {
        return CompanySupplierDataImportConfig::IMPORT_TYPE_COMPANY_SUPPLIER;
    }
}
