<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipProductListDataImport\MerchantRelationshipProductListDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListDataImport\Business\MerchantRelationshipProductListDataImportFacadeInterface getFacade()
 */
class MerchantRelationshipProductListDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importMerchantRelationshipProductList($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImportType(): string
    {
        return MerchantRelationshipProductListDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_PRODUCT_LIST;
    }
}
