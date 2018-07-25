<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\MerchantRelationshipMinimumOrderValueDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Business\MerchantRelationshipMinimumOrderValueDataImportFacadeInterface getFacade()
 */
class MerchantRelationshipMinimumOrderValueDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importMerchantRelationshipMinimumOrderValues($dataImporterConfigurationTransfer);
    }

    /**
     * @return string
     */
    public function getImportType(): string
    {
        return MerchantRelationshipMinimumOrderValueDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE;
    }
}
