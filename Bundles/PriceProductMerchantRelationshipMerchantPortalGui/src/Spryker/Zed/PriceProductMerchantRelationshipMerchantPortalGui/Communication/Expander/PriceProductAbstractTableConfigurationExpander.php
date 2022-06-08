<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface;

class PriceProductAbstractTableConfigurationExpander implements PriceProductAbstractTableConfigurationExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider::FORMAT_STRING_PRICES_URL
     *
     * @var string
     */
    protected const FORMAT_STRING_PRICES_URL = '%s?%s=${row.%s}&%s=${row.%s}&%s=${row.%s}&%s=${row.%s}';

    /**
     * @var string
     */
    protected const FORMAT_STRING_PRICES_URL_DELETE = '%s?%s=${row.%s}&%s=${row.%s}&%s=${row.%s}&%s=${row.%s}&%s=${row.%s}';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider::URL_SAVE_PRICES
     *
     * @var string
     */
    protected const URL_SAVE_PRICES = '/product-merchant-portal-gui/save-price-product-abstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider::URL_DELETE_PRICE
     *
     * @var string
     */
    protected const URL_DELETE_PRICE = '/product-merchant-portal-gui/delete-price-product-abstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider::ID_ROW_ACTION_URL_DELETE_PRICE
     *
     * @var string
     */
    protected const ID_ROW_ACTION_URL_DELETE_PRICE = 'delete-price';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider::TITLE_ROW_ACTION_DELETE
     *
     * @var string
     */
    protected const TITLE_ROW_ACTION_DELETE = 'Delete';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_CUSTOMER = 'Customer';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_QUANTITY = 'Quantity';

    /**
     * @uses \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Controller\PriceProductMerchantRelationshipController::volumePriceDataAction()
     *
     * @var string
     */
    protected const MERCHANT_RELATIONSHIP_VOLUME_PRICE_DATA_URL = '/price-product-merchant-relationship-merchant-portal-gui/price-product-merchant-relationship/volume-price-data/';

    /**
     * @var string
     */
    protected const MERCHANT_RELATIONSHIP_CHOICE_DEFAULT = 'Default Customer';

    /**
     * @var string
     */
    protected const ID_FILTER_IN_MERCHANT_RELATIONSHIPS = 'inMerchantRelationships';

    /**
     * @var string
     */
    protected const TITLE_FILTER_MERCHANT_RELATIONSHIPS = 'Customers';

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function expand(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder = $this->addCustomerColumn($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->replaceVolumeQuantityColumnWithDynamicOne($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder = $this->expandPriceDeleteUrl($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->expandPriceSaveUrl($guiTableConfigurationBuilder);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder
            ->addFilterSelect(
                static::ID_FILTER_IN_MERCHANT_RELATIONSHIPS,
                static::TITLE_FILTER_MERCHANT_RELATIONSHIPS,
                true,
                $this->getMerchantRelationshipChoices($guiTableConfigurationBuilder),
            );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addCustomerColumn(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder
            ->addColumnChip(
                PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
                static::TITLE_COLUMN_CUSTOMER,
                true,
                false,
                'blue',
            )
            ->addEditableColumnSelect(
                PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
                static::TITLE_COLUMN_CUSTOMER,
                false,
                $this->getMerchantRelationshipChoices($guiTableConfigurationBuilder),
            );

        $guiTableConfigurationBuilder = $guiTableConfigurationBuilder->setColumnDisplayKey(
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
            PriceProductTableViewTransfer::MERCHANT_RELATIONSHIP_NAME,
        );

        $guiTableColumnConfigurationTransfers = $this->reorderTableColumns($guiTableConfigurationBuilder->getColumns());

        return $guiTableConfigurationBuilder->setColumns($guiTableColumnConfigurationTransfers);
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function replaceVolumeQuantityColumnWithDynamicOne(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        if (!$guiTableConfigurationBuilder->getEditableConfiguration()) {
            return $guiTableConfigurationBuilder;
        }
        $columns = $guiTableConfigurationBuilder->getEditableConfiguration()->getColumns();

        foreach ($columns as $key => $column) {
            if ($column->getId() == PriceProductTableViewTransfer::VOLUME_QUANTITY) {
                $columns->offsetUnset($key);
            }
        }
        $guiTableConfigurationBuilder->getEditableConfiguration()->setColumns($columns);

        $guiTableConfigurationBuilder->addEditableColumnDynamic(
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
            static::TITLE_COLUMN_QUANTITY,
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
            static::MERCHANT_RELATIONSHIP_VOLUME_PRICE_DATA_URL,
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return array<mixed, string>
     */
    protected function getMerchantRelationshipChoices(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): array
    {
        $merchantRelationshipCriteriaTransfer = new MerchantRelationshipCriteriaTransfer();
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        if ($idMerchant) {
            $merchantRelationshipCriteriaTransfer->setMerchantRelationshipConditions(
                (new MerchantRelationshipConditionsTransfer())->addIdMerchant($idMerchant),
            );
        }
        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        $merchantRelationshipChoices = [
            null => $this->translatorFacade->trans(static::MERCHANT_RELATIONSHIP_CHOICE_DEFAULT),
        ];

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            /** @var \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer */
            $merchantRelationshipChoices[$merchantRelationshipTransfer->getIdMerchantRelationshipOrFail()] = $merchantRelationshipTransfer->getNameOrFail();
        }

        return $merchantRelationshipChoices;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer> $columns
     *
     * @return array<string, \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer>
     */
    protected function reorderTableColumns(array $columns): array
    {
        $customerColumn = [
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP => $columns[PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP],
        ];
        unset($columns[PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP]);

        return $customerColumn + $columns;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function expandPriceSaveUrl(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        return $guiTableConfigurationBuilder->enableInlineDataEditing($this->getSavePriceUrl());
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function expandPriceDeleteUrl(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        $rowActionDeleteTransfer = $guiTableConfigurationBuilder->getRowAction(static::ID_ROW_ACTION_URL_DELETE_PRICE);
        $rowActionDeleteTransfer->setUrl($this->getDeletePriceUrl());

        return $guiTableConfigurationBuilder;
    }

    /**
     * @return string
     */
    protected function getSavePriceUrl(): string
    {
        return sprintf(
            static::FORMAT_STRING_PRICES_URL,
            static::URL_SAVE_PRICES,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
        );
    }

    /**
     * @return string
     */
    protected function getDeletePriceUrl(): string
    {
        return sprintf(
            static::FORMAT_STRING_PRICES_URL_DELETE,
            static::URL_DELETE_PRICE,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
            PriceProductTableViewTransfer::ID_MERCHANT_RELATIONSHIP,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
            PriceProductTableViewTransfer::VOLUME_QUANTITY,
        );
    }
}
