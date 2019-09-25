<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Business\MerchantRelationshipSalesOrderThresholdDataImportBusinessFactory getFactory()
 */
class MerchantRelationshipSalesOrderThresholdDataImportFacade extends AbstractFacade implements MerchantRelationshipSalesOrderThresholdDataImportFacadeInterface
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
    public function importMerchantRelationshipSalesOrderThresholds(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createMerchantRelationshipSalesOrderThresholdDataImport()->import($dataImporterConfigurationTransfer);
    }
}
