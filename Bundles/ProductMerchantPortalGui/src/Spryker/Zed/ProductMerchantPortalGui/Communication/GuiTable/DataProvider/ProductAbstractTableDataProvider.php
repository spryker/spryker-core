<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;

class ProductAbstractTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface
     */
    protected $productMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->productMerchantPortalGuiRepository = $productMerchantPortalGuiRepository;
        $this->localeFacade = $localeFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new MerchantProductTableCriteriaTransfer())
            ->setLocale($this->localeFacade->getCurrentLocale())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $productAbstractCollectionTransfer = $this->productMerchantPortalGuiRepository
            ->getProductAbstractTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $responseData = [
                ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_SKU => $productAbstractTransfer->getSku(),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_IMAGE => $this->getImageUrl($productAbstractTransfer),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_NAME => $productAbstractTransfer->getName(),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_SUPER_ATTRIBUTES => $this->getSuperAttributesColumnData($productAbstractTransfer),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_VARIANTS => $productAbstractTransfer->getConcreteProductCount(),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_CATEGORIES => $productAbstractTransfer->getCategoryNames(),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_STORES => $productAbstractTransfer->getStoreNames(),
                ProductAbstractGuiTableConfigurationProvider::COL_KEY_VISIBILITY => $this->getVisibilityColumnData($productAbstractTransfer),
            ];

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        $paginationTransfer = $productAbstractCollectionTransfer->getPagination();

        if (!$paginationTransfer) {
            return $guiTableDataResponseTransfer;
        }

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(ProductAbstractTransfer $productAbstractTransfer): ?string
    {
        if (!isset($productAbstractTransfer->getImageSets()[0])) {
            return null;
        }

        $productImageSetTransfer = $productAbstractTransfer->getImageSets()[0];

        return isset($productImageSetTransfer->getProductImages()[0])
            ? $productImageSetTransfer->getProductImages()[0]->getExternalUrlSmall()
            : null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string
     */
    protected function getVisibilityColumnData(ProductAbstractTransfer $productAbstractTransfer): string
    {
        if ($productAbstractTransfer->getIsActive()) {
            return $this->translatorFacade->trans(ProductAbstractGuiTableConfigurationProvider::COLUMN_DATA_VISIBILITY_ONLINE);
        }

        return $this->translatorFacade->trans(ProductAbstractGuiTableConfigurationProvider::COLUMN_DATA_VISIBILITY_OFFLINE);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string[]
     */
    protected function getSuperAttributesColumnData(ProductAbstractTransfer $productAbstractTransfer): array
    {
        return array_map(function (string $attributeKey): string {
            return ucwords(str_replace('_', ' ', $attributeKey));
        }, array_keys($productAbstractTransfer->getAttributes()));
    }
}
