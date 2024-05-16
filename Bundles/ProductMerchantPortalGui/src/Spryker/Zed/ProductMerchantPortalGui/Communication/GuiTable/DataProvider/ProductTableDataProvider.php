<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;

class ProductTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var int
     */
    protected int $idProductAbstract;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface
     */
    protected ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface
     */
    protected LocalizedAttributesExtractorInterface $localizedAttributesExtractor;

    /**
     * @var array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface>
     */
    protected array $productConcreteTableExpanderPlugins;

    /**
     * @param int $idProductAbstract
     * @param \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface $localizedAttributesExtractor
     * @param array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface> $productConcreteTableExpanderPlugins
     */
    public function __construct(
        int $idProductAbstract,
        ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        LocalizedAttributesExtractorInterface $localizedAttributesExtractor,
        array $productConcreteTableExpanderPlugins = []
    ) {
        $this->idProductAbstract = $idProductAbstract;
        $this->productMerchantPortalGuiRepository = $productMerchantPortalGuiRepository;
        $this->localeFacade = $localeFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->translatorFacade = $translatorFacade;
        $this->localizedAttributesExtractor = $localizedAttributesExtractor;
        $this->productConcreteTableExpanderPlugins = $productConcreteTableExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new ProductTableCriteriaTransfer())
            ->setIdProductAbstract($this->idProductAbstract)
            ->setLocale($this->localeFacade->getCurrentLocale())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $productConcreteCollectionTransfer = $this->productMerchantPortalGuiRepository->getProductTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();
        $localeTransfer = $criteriaTransfer->getLocaleOrFail();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $responseData = $productConcreteTransfer->toArray(true, true);
            $responseData[ProductGuiTableConfigurationProvider::COL_KEY_NAME] = $productConcreteTransfer->getName();
            $responseData[ProductGuiTableConfigurationProvider::COL_KEY_IMAGE] = $this->getImageUrl($productConcreteTransfer);
            $responseData[ProductGuiTableConfigurationProvider::COL_KEY_SUPER_ATTRIBUTES] = $this->getSuperAttributesColumnData(
                $productConcreteTransfer,
                $localeTransfer,
            );
            $responseData[ProductGuiTableConfigurationProvider::COL_KEY_STATUS] = $this->getStatusColumnData($productConcreteTransfer);

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        /** @var \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer */
        $paginationTransfer = $productConcreteCollectionTransfer->requirePagination()->getPagination();

        $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->requirePage()->getPage())
            ->setPageSize($paginationTransfer->requireMaxPerPage()->getMaxPerPage())
            ->setTotal($paginationTransfer->requireNbResults()->getNbResults());

        foreach ($this->productConcreteTableExpanderPlugins as $productConcreteTableExpanderPlugin) {
            $guiTableDataResponseTransfer = $productConcreteTableExpanderPlugin->expandDataResponse($guiTableDataResponseTransfer);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    protected function getStatusColumnData(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $isActiveColumnData = $productConcreteTransfer->getIsActive()
            ? ProductGuiTableConfigurationProvider::COLUMN_DATA_STATUS_ACTIVE
            : ProductGuiTableConfigurationProvider::COLUMN_DATA_STATUS_INACTIVE;

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

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    protected function getSuperAttributesColumnData(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): array {
        return array_values($this->localizedAttributesExtractor->extractCombinedSuperAttributeNames(
            $productConcreteTransfer->getAttributes(),
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer,
        ));
    }
}
