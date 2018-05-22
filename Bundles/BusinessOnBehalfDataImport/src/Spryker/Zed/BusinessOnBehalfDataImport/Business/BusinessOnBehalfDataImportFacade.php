<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\BusinessOnBehalfDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\BusinessOnBehalfDataImport\Business\BusinessOnBehalfDataImportBusinessFactory getFactory()
 */
class BusinessOnBehalfDataImportFacade extends AbstractFacade implements BusinessOnBehalfDataImportFacadeInterface
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
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer): DataImporterReportTransfer
    {
        return $this->getFactory()->createCompanyBusinessUnitDataImport()->import($dataImporterConfigurationTransfer);
    }
}
