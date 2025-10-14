<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CurrencyDataImport\Business\CurrencyDataImportBusinessFactory getFactory()
 */
class CurrencyDataImportFacade extends AbstractFacade implements CurrencyDataImportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer = null
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCurrencyStore(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getCurrencyStoreDataImporter($dataImporterConfigurationTransfer)
            ->import($dataImporterConfigurationTransfer);
    }
}
