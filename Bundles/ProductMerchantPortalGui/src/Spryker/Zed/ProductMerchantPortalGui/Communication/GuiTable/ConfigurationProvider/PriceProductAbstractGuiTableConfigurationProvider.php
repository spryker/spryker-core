<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableButtonTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProviderInterface;

class PriceProductAbstractGuiTableConfigurationProvider implements PriceProductAbstractGuiTableConfigurationProviderInterface
{
    protected const FORMAT_STRING_DATA_URL = '%s?%s=%s';
    protected const FORMAT_STRING_PRICES_URL = '%s?%s=${row.%s}&%s=${row.%s}';

    protected const TITLE_ROW_ACTION_DELETE = 'Delete';
    protected const TITLE_EDITABLE_BUTTON = 'Add';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::tableDataAction()
     */
    protected const DATA_URL = '/product-merchant-portal-gui/update-product-abstract/table-data';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\SavePriceProductAbstractController::indexAction()
     */
    protected const URL_SAVE_PRICES = '/product-merchant-portal-gui/save-price-product-abstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\DeletePriceProductAbstractController::indexAction()
     */
    protected const URL_DELETE_PRICE = '/product-merchant-portal-gui/delete-price-product-abstract';

    protected const ID_ROW_ACTION_URL_DELETE_PRICE = 'delete-price';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProviderInterface
     */
    protected $priceProductGuiTableConfigurationBuilderProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProviderInterface $priceProductGuiTableConfigurationBuilderProvider
     */
    public function __construct(PriceProductGuiTableConfigurationBuilderProviderInterface $priceProductGuiTableConfigurationBuilderProvider)
    {
        $this->priceProductGuiTableConfigurationBuilderProvider = $priceProductGuiTableConfigurationBuilderProvider;
    }

    /**
     * @param int $idProductAbstract
     * @param mixed[] $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductAbstract, array $initialData = []): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->priceProductGuiTableConfigurationBuilderProvider->getPriceProductGuiTableConfigurationBuilder();

        $dataSourceUrl = sprintf(
            static::FORMAT_STRING_DATA_URL,
            static::DATA_URL,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            $idProductAbstract
        );
        $deletePriceUrl = sprintf(
            static::FORMAT_STRING_PRICES_URL,
            static::URL_DELETE_PRICE,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS
        );
        $savePricesUrl = sprintf(
            static::FORMAT_STRING_PRICES_URL,
            static::URL_SAVE_PRICES,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT
        );
        $formInputName = sprintf('%s[%s]', ProductAbstractForm::BLOCK_PREFIX, ProductAbstractTransfer::PRICES);

        $guiTableConfigurationBuilder->setDataSourceUrl($dataSourceUrl)
            ->addRowActionUrl(static::ID_ROW_ACTION_URL_DELETE_PRICE, static::TITLE_ROW_ACTION_DELETE, $deletePriceUrl)
            ->enableInlineDataEditing($savePricesUrl, 'POST')
            ->enableAddingNewRows($formInputName, $initialData, [
                GuiTableEditableButtonTransfer::TITLE => static::TITLE_EDITABLE_BUTTON,
            ]);

        return $guiTableConfigurationBuilder->createConfiguration();
    }
}
