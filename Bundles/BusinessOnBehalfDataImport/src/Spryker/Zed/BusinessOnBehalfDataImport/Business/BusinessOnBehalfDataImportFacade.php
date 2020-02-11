<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCompanyUser(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer): DataImporterReportTransfer
    {
        return $this->getFactory()->getCompanyUserDataImport()->import($dataImporterConfigurationTransfer);
    }
}
