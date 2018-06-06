<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanySupplierDataImport\Business\CompanySupplierDataImportBusinessFactory getFactory()
 */
class CompanySupplierDataImportFacade extends AbstractFacade implements CompanySupplierDataImportFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCompanyType(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createCompanyTypeDataImport()->import($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCompanySupplier(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createCompanySupplierDataImport()->import($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importProductPrice(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createCompanySupplierProductPriceDataImport()->import($dataImporterConfigurationTransfer);
    }
}
