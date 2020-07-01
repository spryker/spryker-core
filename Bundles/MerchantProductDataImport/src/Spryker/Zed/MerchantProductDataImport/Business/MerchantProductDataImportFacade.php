<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductDataImport\Persistence\MerchantProductDataImportRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductDataImport\Persistence\MerchantProductDataImportEntityManagerInterface getEntityManager()
 */
class MerchantProductDataImportFacade extends AbstractFacade implements MerchantProductDataImportFacadeInterface
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
    public function importMerchantProduct(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()->getMerchantProductDataImporter()->import($dataImporterConfigurationTransfer);
    }
}
