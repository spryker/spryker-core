<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTableRowDataTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;

class ProductOfferTableDataProvider implements ProductOfferTableDataProviderInterface
{
    protected const COLUMN_DATA_IS_NEVER_OUT_OF_STOCK = 'always in stock';
    protected const COLUMN_DATA_VISIBILITY_ONLINE = 'online';
    protected const COLUMN_DATA_VISIBILITY_OFFLINE = 'offline';

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
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface $productNameBuilder
     */
    public function __construct(
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        ProductNameBuilderInterface $productNameBuilder
    ) {
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->translatorFacade = $translatorFacade;
        $this->productNameBuilder = $productNameBuilder;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    public function getProductOfferTableData(ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer): GuiTableDataTransfer
    {
        $productOfferTableDataTransfer = $this->productOfferMerchantPortalGuiRepository->getProductOfferTableData($productOfferTableCriteriaTransfer);
        $productTableDataArray = [];

        foreach ($productOfferTableDataTransfer->getRows() as $productOfferTableRowDataTransfer) {
            $productTableDataArray[] = [
                ProductOfferTable::COL_KEY_OFFER_REFERENCE => $productOfferTableRowDataTransfer->getOfferReference(),
                ProductOfferTable::COL_KEY_MERCHANT_SKU => $productOfferTableRowDataTransfer->getMerchantSku(),
                ProductOfferTable::COL_KEY_CONCRETE_SKU => $productOfferTableRowDataTransfer->getConcreteSku(),
                ProductOfferTable::COL_KEY_IMAGE => $productOfferTableRowDataTransfer->getImage(),
                ProductOfferTable::COL_KEY_PRODUCT_NAME => $this->getNameColumnData($productOfferTableRowDataTransfer),
                ProductOfferTable::COL_KEY_STORES => $this->getStoresColumnData($productOfferTableRowDataTransfer),
                ProductOfferTable::COL_KEY_STOCK => $this->getStockColumnData($productOfferTableRowDataTransfer),
                ProductOfferTable::COL_KEY_VISIBILITY => $this->getVisibilityColumnData($productOfferTableRowDataTransfer),
                ProductOfferTable::COL_KEY_VALID_FROM => $this->getFormattedDateTime($productOfferTableRowDataTransfer->getValidFrom()),
                ProductOfferTable::COL_KEY_VALID_TO => $this->getFormattedDateTime($productOfferTableRowDataTransfer->getValidTo()),
                ProductOfferTable::COL_KEY_CREATED_AT => $this->getFormattedDateTime($productOfferTableRowDataTransfer->getCreatedAt()),
                ProductOfferTable::COL_KEY_UPDATED_AT => $this->getFormattedDateTime($productOfferTableRowDataTransfer->getUpdatedAt()),
            ];
        }

        $paginationTransfer = $productOfferTableDataTransfer->getPagination();

        return (new GuiTableDataTransfer())->setData($productTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return string|null
     */
    protected function getNameColumnData(ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer): ?string
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer($productOfferTableRowDataTransfer);

        return $this->productNameBuilder->buildProductName($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(
        ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
    ): ProductConcreteTransfer {
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setAttributes($productOfferTableRowDataTransfer->getProductConcreteLocalizedAttributes())
            ->setName($productOfferTableRowDataTransfer->getProductConcreteName());

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setAttributes($productOfferTableRowDataTransfer->getProductConcreteAttributes())
            ->setLocalizedAttributes(new ArrayObject([$localizedAttributesTransfer]));

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return string
     */
    protected function getStoresColumnData(ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer)
    {
        $storesString = $productOfferTableRowDataTransfer->getStores();
        $stores = explode(',', $storesString);

        return implode(', ', $stores);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return int|string|null
     */
    protected function getStockColumnData(ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer)
    {
        if ($productOfferTableRowDataTransfer->getIsNeverOutOfStock()) {
            return $this->translatorFacade->trans(static::COLUMN_DATA_IS_NEVER_OUT_OF_STOCK);
        }

        $quantity = $productOfferTableRowDataTransfer->getQuantity();

        return $quantity === null ? $quantity : $quantity->toInt();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return string
     */
    protected function getVisibilityColumnData(ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer): string
    {
        if ($productOfferTableRowDataTransfer->getIsActive()) {
            return $this->translatorFacade->trans(static::COLUMN_DATA_VISIBILITY_ONLINE);
        }

        return $this->translatorFacade->trans(static::COLUMN_DATA_VISIBILITY_OFFLINE);
    }

    /**
     * @param string|null $dateTime
     *
     * @return string|null
     */
    protected function getFormattedDateTime(?string $dateTime): ?string
    {
        return $dateTime ? $this->utilDateTimeService->formatDateTimeToIso($dateTime) : null;
    }
}
