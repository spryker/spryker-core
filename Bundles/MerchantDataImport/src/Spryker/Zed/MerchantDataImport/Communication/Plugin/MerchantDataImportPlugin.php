<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantDataImport\MerchantDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantDataImport\Business\MerchantDataImportFacadeInterface getFacade()
 */
class MerchantDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->import($dataImporterConfigurationTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return MerchantDataImportConfig::IMPORT_TYPE_MERCHANT;
    }
}
