<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOptionDataImport\Business\MerchantProductOptionDataImportBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOptionDataImport\Persistence\MerchantProductOptionDataImportRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOptionDataImport\Persistence\MerchantProductOptionDataImportEntityManagerInterface getEntityManager()
 */
class MerchantProductOptionDataImportFacade extends AbstractFacade implements MerchantProductOptionDataImportFacadeInterface
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
    public function importMerchantProductOptionGroupData(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()->getMerchantProductOptionGroupDataImport()->import($dataImporterConfigurationTransfer);
    }
}
