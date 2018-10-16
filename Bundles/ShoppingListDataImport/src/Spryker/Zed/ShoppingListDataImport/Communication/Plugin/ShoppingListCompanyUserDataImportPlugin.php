<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;

/**
 * @method \Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportFacadeInterface getFacade()
 */
class ShoppingListCompanyUserDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
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
        return $this->getFacade()->importShoppingListCompanyUser($dataImporterConfigurationTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_USER;
    }
}
