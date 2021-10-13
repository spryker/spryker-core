<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CategoryDataImport\CategoryDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryDataImport\Business\CategoryDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryDataImport\CategoryDataImportConfig getConfig()
 */
class CategoryStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes the category store data importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importCategoryStore($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns name of the category store import type.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return CategoryDataImportConfig::IMPORT_TYPE_CATEGORY_STORE;
    }
}
