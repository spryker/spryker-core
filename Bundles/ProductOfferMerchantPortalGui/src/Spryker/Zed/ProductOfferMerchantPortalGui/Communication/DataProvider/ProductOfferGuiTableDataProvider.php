<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;

class ProductOfferGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var string
     */
    protected const COLUMN_DATA_APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'Pending';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface
     */
    protected ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    protected ProductNameBuilderInterface $productNameBuilder;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface $productNameBuilder
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductNameBuilderInterface $productNameBuilder,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->translatorFacade = $translatorFacade;
        $this->productNameBuilder = $productNameBuilder;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new ProductOfferTableCriteriaTransfer())
            ->setLocale($this->localeFacade->getCurrentLocale())
            ->setMerchantReference($this->merchantUserFacade->getCurrentMerchantUser()->getMerchantOrFail()->getMerchantReference());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $productOfferCollectionTransfer = $this->productOfferMerchantPortalGuiRepository->getProductOfferTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();
        $localeTransfer = $criteriaTransfer->getLocaleOrFail();

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $responseData = [
                ProductOfferTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_MERCHANT_SKU => $productOfferTransfer->getMerchantSku(),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_CONCRETE_SKU => $productOfferTransfer->getConcreteSku(),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_IMAGE => $this->getImageUrl($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_PRODUCT_NAME => $this->getNameColumnData($productOfferTransfer, $localeTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_STORES => $this->getStoresColumnData($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_STOCK => $this->getStockColumnData($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_STATUS => $this->getStatusColumnData($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_APPROVAL_STATUS => $this->getApprovalStatusColumnData($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_VALID_FROM => $this->getValidFromColumnData($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_VALID_TO => $this->getValidToColumnData($productOfferTransfer),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_CREATED_AT => $productOfferTransfer->getCreatedAt(),
                ProductOfferGuiTableConfigurationProvider::COL_KEY_UPDATED_AT => $productOfferTransfer->getUpdatedAt(),
            ];

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        $paginationTransfer = $productOfferCollectionTransfer->getPaginationOrFail();

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPageOrFail())
            ->setPageSize($paginationTransfer->getMaxPerPageOrFail())
            ->setTotal($paginationTransfer->getNbResultsOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function getNameColumnData(ProductOfferTransfer $productOfferTransfer, LocaleTransfer $localeTransfer): ?string
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setAttributes($productOfferTransfer->getProductAttributes())
            ->setLocalizedAttributes($productOfferTransfer->getProductLocalizedAttributes());

        return $this->productNameBuilder->buildProductConcreteName($productConcreteTransfer, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array<string>
     */
    protected function getStoresColumnData(ProductOfferTransfer $productOfferTransfer): array
    {
        $storeTransfers = $productOfferTransfer->getStores();
        $storeNames = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getNameOrFail();
        }

        return $storeNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return int|null
     */
    protected function getStockColumnData(ProductOfferTransfer $productOfferTransfer): ?int
    {
        if (!$productOfferTransfer->getProductOfferStocks()->count()) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer */
        $productOfferStockTransfer = $productOfferTransfer->getProductOfferStocks()[0];

        $quantity = $productOfferStockTransfer->getQuantity();

        return $quantity === null ? $quantity : $quantity->toInt();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string
     */
    protected function getStatusColumnData(ProductOfferTransfer $productOfferTransfer): string
    {
        if ($productOfferTransfer->getIsActive()) {
            return $this->translatorFacade->trans(ProductOfferGuiTableConfigurationProvider::COLUMN_DATA_STATUS_ACTIVE);
        }

        return $this->translatorFacade->trans(ProductOfferGuiTableConfigurationProvider::COLUMN_DATA_STATUS_INACTIVE);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string|null
     */
    protected function getValidFromColumnData(ProductOfferTransfer $productOfferTransfer): ?string
    {
        $productOfferValidityTransfer = $productOfferTransfer->getProductOfferValidity();

        if (!$productOfferValidityTransfer) {
            return null;
        }

        return $productOfferValidityTransfer->getValidFrom();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string|null
     */
    protected function getValidToColumnData(ProductOfferTransfer $productOfferTransfer): ?string
    {
        $productOfferValidityTransfer = $productOfferTransfer->getProductOfferValidity();

        if (!$productOfferValidityTransfer) {
            return null;
        }

        return $productOfferValidityTransfer->getValidTo();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(ProductOfferTransfer $productOfferTransfer): ?string
    {
        return isset($productOfferTransfer->getProductImages()[0])
            ? $productOfferTransfer->getProductImages()[0]->getExternalUrlSmall()
            : null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string
     */
    protected function getApprovalStatusColumnData(ProductOfferTransfer $productOfferTransfer): string
    {
        if ($productOfferTransfer->getApprovalStatus() === static::APPROVAL_STATUS_WAITING_FOR_APPROVAL) {
            return $this->translatorFacade->trans(static::COLUMN_DATA_APPROVAL_STATUS_WAITING_FOR_APPROVAL);
        }

        return $this->translatorFacade->trans($productOfferTransfer->getApprovalStatusOrFail());
    }
}
