<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShoppingListDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\Business\ProductOfferShoppingListDataImportBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\Persistence\ProductOfferShoppingListDataImportRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\Persistence\ProductOfferShoppingListDataImportEntityManagerInterface getEntityManager()
 */
class ProductOfferShoppingListDataImportFacade extends AbstractFacade implements ProductOfferShoppingListDataImportFacadeInterface
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
    public function importProductOfferShoppingListItem(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()->getProductOfferShoppingListItemDataImporter()->import($dataImporterConfigurationTransfer);
    }
}
