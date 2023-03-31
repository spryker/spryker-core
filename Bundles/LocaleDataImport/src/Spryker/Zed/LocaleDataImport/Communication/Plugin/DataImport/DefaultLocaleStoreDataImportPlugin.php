<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\LocaleDataImport\LocaleDataImportConfig;

/**
 * @method \Spryker\Zed\LocaleDataImport\Business\LocaleDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\LocaleDataImport\LocaleDataImportConfig getConfig()
 * @method \Spryker\Zed\LocaleDataImport\Communication\LocaleDataImportCommunicationFactory getFactory()
 */
class DefaultLocaleStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports default locales for stores.
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
        return $this->getFacade()->importDefaultLocaleStore($dataImporterConfigurationTransfer);
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
        return LocaleDataImportConfig::IMPORT_TYPE_DEFAULT_LOCALE_STORE;
    }
}
