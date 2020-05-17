<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable;

use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductOfferTableDataProvider extends AbstractTableDataProvider
{
    public const COLUMN_DATA_VISIBILITY_ONLINE = 'online';
    protected const COLUMN_DATA_VISIBILITY_OFFLINE = 'offline';
    protected const COLUMN_DATA_IS_NEVER_OUT_OF_STOCK = 'always in stock';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface
     */
    protected $productOfferMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    protected $productNameBuilder;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface
     */
    protected $guiTableDataRequestBuilder;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface $productNameBuilder
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface $guiTableDataRequestBuilder
     */
    public function __construct(
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        ProductNameBuilderInterface $productNameBuilder,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        GuiTableDataRequestBuilderInterface $guiTableDataRequestBuilder
    ) {
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->translatorFacade = $translatorFacade;
        $this->productNameBuilder = $productNameBuilder;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->guiTableDataRequestBuilder = $guiTableDataRequestBuilder;
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface
     */
    protected function getGuiTableDataRequestBuilder(): GuiTableDataRequestBuilderInterface
    {
        return $this->guiTableDataRequestBuilder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(Request $request): AbstractTransfer
    {
        return (new ProductOfferTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataTransfer
    {
        $productOfferCollectionTransfer = $this->productOfferMerchantPortalGuiRepository->getProductOfferTableData($criteriaTransfer);
        $productTableDataArray = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productTableDataArray[] = [
                ProductOfferTable::COL_KEY_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                ProductOfferTable::COL_KEY_MERCHANT_SKU => $productOfferTransfer->getMerchantSku(),
                ProductOfferTable::COL_KEY_CONCRETE_SKU => $productOfferTransfer->getConcreteSku(),
                ProductOfferTable::COL_KEY_IMAGE => $this->getImageUrl($productOfferTransfer),
                ProductOfferTable::COL_KEY_PRODUCT_NAME => $this->getNameColumnData($productOfferTransfer),
                ProductOfferTable::COL_KEY_STORES => $this->getStoresColumnData($productOfferTransfer),
                ProductOfferTable::COL_KEY_STOCK => $this->getStockColumnData($productOfferTransfer),
                ProductOfferTable::COL_KEY_VISIBILITY => $this->getVisibilityColumnData($productOfferTransfer),
                ProductOfferTable::COL_KEY_VALID_FROM => $this->getValidFromColumnData($productOfferTransfer),
                ProductOfferTable::COL_KEY_VALID_TO => $this->getValidToColumnData($productOfferTransfer),
                ProductOfferTable::COL_KEY_CREATED_AT => $this->getFormattedDateTime($productOfferTransfer->getCreatedAt()),
                ProductOfferTable::COL_KEY_UPDATED_AT => $this->getFormattedDateTime($productOfferTransfer->getUpdatedAt()),
            ];
        }

        $paginationTransfer = $productOfferCollectionTransfer->getPagination();

        return (new GuiTableDataTransfer())->setData($productTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string|null
     */
    protected function getNameColumnData(ProductOfferTransfer $productOfferTransfer): ?string
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setAttributes($productOfferTransfer->getProductAttributes())
            ->setLocalizedAttributes($productOfferTransfer->getProductLocalizedAttributes());

        return $this->productNameBuilder->buildProductName($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string[]
     */
    protected function getStoresColumnData(ProductOfferTransfer $productOfferTransfer): array
    {
        $storeTransfers = $productOfferTransfer->getStores();
        $storeNames = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return int|string|null
     */
    protected function getStockColumnData(ProductOfferTransfer $productOfferTransfer)
    {
        $productOfferStockTransfer = $productOfferTransfer->getProductOfferStock();

        if (!$productOfferStockTransfer) {
            return null;
        }

        if ($productOfferStockTransfer->getIsNeverOutOfStock()) {
            return $this->translatorFacade->trans(static::COLUMN_DATA_IS_NEVER_OUT_OF_STOCK);
        }

        $quantity = $productOfferStockTransfer->getQuantity();

        return $quantity === null ? $quantity : $quantity->toInt();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return string
     */
    protected function getVisibilityColumnData(ProductOfferTransfer $productOfferTransfer): string
    {
        if ($productOfferTransfer->getIsActive()) {
            return $this->translatorFacade->trans(static::COLUMN_DATA_VISIBILITY_ONLINE);
        }

        return $this->translatorFacade->trans(static::COLUMN_DATA_VISIBILITY_OFFLINE);
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

        return $this->getFormattedDateTime($productOfferValidityTransfer->getValidFrom());
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

        return $this->getFormattedDateTime($productOfferValidityTransfer->getValidTo());
    }

    /**
     * @param string|null $dateTime
     *
     * @return string|null
     */
    protected function getFormattedDateTime(?string $dateTime): ?string
    {
        return $dateTime ? $this->utilDateTimeService->formatDateTimeToIso8601($dateTime) : null;
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
}
