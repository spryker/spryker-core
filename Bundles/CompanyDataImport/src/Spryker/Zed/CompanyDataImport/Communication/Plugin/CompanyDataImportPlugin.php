<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\CompanyDataImport\CompanyDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyDataImport\Business\CompanyDataImportFacadeInterface getFacade()
 */
class CompanyDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        return $this->getFacade()->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return string
     */
    public function getImportType()
    {
        return CompanyDataImportConfig::IMPORT_TYPE_COMPANY;
    }
}
