<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableButtonTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProviderInterface;

class PriceProductConcreteGuiTableConfigurationProvider implements PriceProductConcreteGuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    protected const FORMAT_STRING_DATA_URL = '%s?%s=%s';

    /**
     * @var string
     */
    protected const FORMAT_STRING_PRICES_URL = '%s?%s=${row.%s}&%s=${row.%s}&%s=${row.%s}';

    /**
     * @var string
     */
    protected const TITLE_ROW_ACTION_DELETE = 'Delete';

    /**
     * @var string
     */
    protected const TITLE_EDITABLE_BUTTON = 'Add';

    /**
     * @var string
     */
    protected const VARIANT_EDITABLE_BUTTON = 'outline';

    /**
     * @var string
     */
    protected const SIZE_EDITABLE_BUTTON = 'sm';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::BLOCK_PREFIX
     *
     * @var string
     */
    protected const BLOCK_PREFIX_PRODUCT_CONCRETE_EDIT_FORM = 'productConcreteEdit';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteForm::BLOCK_PREFIX
     *
     * @var string
     */
    protected const BLOCK_PREFIX_PRODUCT_CONCRETE_FORM = 'productConcrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductConcreteController::priceTableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/product-merchant-portal-gui/update-product-concrete/price-table-data';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\SavePriceProductConcreteController::indexAction()
     *
     * @var string
     */
    protected const URL_SAVE_PRICES = '/product-merchant-portal-gui/save-price-product-concrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\DeletePriceProductConcreteController::indexAction()
     *
     * @var string
     */
    protected const URL_DELETE_PRICE = '/product-merchant-portal-gui/delete-price-product-concrete';

    /**
     * @var string
     */
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
     * @param int $idProductConcrete
     * @param array<mixed> $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductConcrete, array $initialData = []): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->priceProductGuiTableConfigurationBuilderProvider->getPriceProductGuiTableConfigurationBuilder();

        $dataSourceUrl = sprintf(
            static::FORMAT_STRING_DATA_URL,
            static::DATA_URL,
            PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE,
            $idProductConcrete,
        );
        $deletePriceUrl = sprintf(
            static::FORMAT_STRING_PRICES_URL,
            static::URL_DELETE_PRICE,
            PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE,
            PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE,
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
        );
        $savePricesUrl = sprintf(
            static::FORMAT_STRING_PRICES_URL,
            static::URL_SAVE_PRICES,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE,
            PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
        );
        $formInputName = sprintf(
            '%s[%s][%s]',
            static::BLOCK_PREFIX_PRODUCT_CONCRETE_EDIT_FORM,
            static::BLOCK_PREFIX_PRODUCT_CONCRETE_FORM,
            ProductConcreteTransfer::PRICES,
        );

        $guiTableConfigurationBuilder->setDataSourceUrl($dataSourceUrl)
            ->addRowActionHttp(static::ID_ROW_ACTION_URL_DELETE_PRICE, static::TITLE_ROW_ACTION_DELETE, $deletePriceUrl)
            ->enableInlineDataEditing($savePricesUrl, 'POST')
            ->enableAddingNewRows(
                $formInputName,
                $initialData,
                [
                    GuiTableEditableButtonTransfer::TITLE => static::TITLE_EDITABLE_BUTTON,
                    GuiTableEditableButtonTransfer::VARIANT => static::VARIANT_EDITABLE_BUTTON,
                    GuiTableEditableButtonTransfer::SIZE => static::SIZE_EDITABLE_BUTTON,
                ],
            );

        return $guiTableConfigurationBuilder->createConfiguration();
    }
}
