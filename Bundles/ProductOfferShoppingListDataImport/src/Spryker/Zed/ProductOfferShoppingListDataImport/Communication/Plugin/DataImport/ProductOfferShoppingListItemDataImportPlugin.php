<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferShoppingListDataImport\ProductOfferShoppingListDataImportConfig;

/**
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\Business\ProductOfferShoppingListDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\ProductOfferShoppingListDataImportConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\Communication\ProductOfferShoppingListDataImportCommunicationFactory getFactory()
 */
class ProductOfferShoppingListItemDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports ShoppingListProductOffer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importProductOfferShoppingListItem($dataImporterConfigurationTransfer);
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
        return ProductOfferShoppingListDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_SHOPPING_LIST_ITEM;
    }
}
