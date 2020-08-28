<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentProductDataImport\Business\ContentProductDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig getConfig()
 */
class ContentProductAbstractListDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports content item abstract product list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        return $this->getFacade()->importProductAbstractListTerm($dataImporterConfigurationTransfer);
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
        return ContentProductDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT;
    }
}
