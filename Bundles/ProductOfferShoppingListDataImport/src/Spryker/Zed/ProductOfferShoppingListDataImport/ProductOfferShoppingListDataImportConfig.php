<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShoppingListDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductOfferShoppingListDataImportConfig extends DataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_OFFER_SHOPPING_LIST_ITEM = 'product-offer-shopping-list-item';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductOfferShoppingListItemDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'product_offer_shopping_list.csv',
            static::IMPORT_TYPE_PRODUCT_OFFER_SHOPPING_LIST_ITEM,
        );
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..',
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
