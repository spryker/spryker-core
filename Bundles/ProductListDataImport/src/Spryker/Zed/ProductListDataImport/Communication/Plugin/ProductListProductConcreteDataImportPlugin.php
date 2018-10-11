<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductListDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * @method \Spryker\Zed\ProductListDataImport\Business\ProductListDataImportFacadeInterface getFacade()
 */
class ProductListProductConcreteDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
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
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importProductListProductConcrete($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST_PRODUCT_CONCRETE;
    }
}
