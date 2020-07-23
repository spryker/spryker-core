<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;

class ProductTableDataProvider extends AbstractGuiTableDataProvider
{
    public const COLUMN_DATA_STATUS_ACTIVE = 'Active';
    protected const COLUMN_DATA_STATUS_INACTIVE = 'Inactive';

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
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface $productNameBuilder
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductNameBuilderInterface $productNameBuilder,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->translatorFacade = $translatorFacade;
        $this->productNameBuilder = $productNameBuilder;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new ProductTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $localeTransfer = (new LocaleTransfer())->setIdLocale($criteriaTransfer->getIdLocale());
        $productConcreteCollectionTransfer = $this->productOfferMerchantPortalGuiRepository->getProductTableData($criteriaTransfer);
        $productTableDataArray = [];

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $productTableDataArray[] = [
                ProductConcreteTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
                ProductGuiTableConfigurationProvider::COL_KEY_SKU => $productConcreteTransfer->getSku(),
                ProductGuiTableConfigurationProvider::COL_KEY_NAME => $this->productNameBuilder->buildProductConcreteName($productConcreteTransfer, $localeTransfer),
                ProductGuiTableConfigurationProvider::COL_KEY_STORES => $this->getStoresColumnData($productConcreteTransfer),
                ProductGuiTableConfigurationProvider::COL_KEY_IMAGE => $this->getImageUrl($productConcreteTransfer),
                ProductGuiTableConfigurationProvider::COL_KEY_STATUS => $this->getStatusColumnData($productConcreteTransfer),
                ProductGuiTableConfigurationProvider::COL_KEY_VALID_FROM => $productConcreteTransfer->getValidFrom(),
                ProductGuiTableConfigurationProvider::COL_KEY_VALID_TO => $productConcreteTransfer->getValidTo(),
                ProductGuiTableConfigurationProvider::COL_KEY_OFFERS => $productConcreteTransfer->getNumberOfOffers(),
            ];
        }

        $paginationTransfer = $productConcreteCollectionTransfer->getPagination();

        return (new GuiTableDataResponseTransfer())->setData($productTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string[]
     */
    protected function getStoresColumnData(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $storeTransfers = $productConcreteTransfer->getStores();
        $storeNames = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    protected function getStatusColumnData(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $isActiveColumnData = $productConcreteTransfer->getIsActive()
            ? static::COLUMN_DATA_STATUS_ACTIVE
            : static::COLUMN_DATA_STATUS_INACTIVE;

        return $this->translatorFacade->trans($isActiveColumnData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        if (!isset($productConcreteTransfer->getImageSets()[0])) {
            return null;
        }

        $productImageSetTransfer = $productConcreteTransfer->getImageSets()[0];

        return isset($productImageSetTransfer->getProductImages()[0])
            ? $productImageSetTransfer->getProductImages()[0]->getExternalUrlSmall()
            : null;
    }
}
