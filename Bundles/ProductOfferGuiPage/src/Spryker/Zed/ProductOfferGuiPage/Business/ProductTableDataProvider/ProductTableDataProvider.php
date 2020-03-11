<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface;

class ProductTableDataProvider implements ProductTableDataProviderInterface
{
    protected const EXTENDED_PRODUCT_CONCRETE_NAME_SEPARATOR = ', ';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface
     */
    protected $productOfferGuiPageRepository;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface $productImageFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface $ProductOfferGuiPageRepository
     */
    public function __construct(
        ProductOfferGuiPageToProductImageFacadeInterface $productImageFacade,
        ProductOfferGuiPageRepositoryInterface $ProductOfferGuiPageRepository
    ) {

        $this->productImageFacade = $productImageFacade;
        $this->productOfferGuiPageRepository = $ProductOfferGuiPageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductConcreteCollectionTransfer
    {
        $productConcreteCollectionTransfer = $this->productOfferGuiPageRepository
            ->getConcreteProductsForProductTable($productTableCriteriaTransfer);
        $productConcreteCollectionTransfer = $this->addProductImagesToConcreteProducts(
            $productConcreteCollectionTransfer,
            $productTableCriteriaTransfer->requireLocale()->getLocale()
        );
        $productConcreteCollectionTransfer = $this->transformProductConcreteNames($productConcreteCollectionTransfer);

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function addProductImagesToConcreteProducts(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        LocaleTransfer $localeTransfer
    ): ProductConcreteCollectionTransfer {
        $productImageSetCollectionTransfer = $this->findProductConcreteProductImageSetsForLocale($productConcreteCollectionTransfer, $localeTransfer);
        $productConcreteCollectionTransfer = $this->mergeConcreteProductsWithProductImageSets(
            $productConcreteCollectionTransfer,
            $productImageSetCollectionTransfer
        );

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    protected function findProductConcreteProductImageSetsForLocale(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        LocaleTransfer $localeTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetCriteriaTransfer = new ProductImageSetCriteriaTransfer();
        $productImageSetCriteriaTransfer->setProductConcreteIds(
            $this->extractProductConcreteIds($productConcreteCollectionTransfer)
        );
        $productImageSetCriteriaTransfer->setLocaleId($localeTransfer->requireIdLocale()->getIdLocale());

        return $this->productImageFacade->getProductImageSets($productImageSetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function mergeConcreteProductsWithProductImageSets(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        foreach ($productConcreteCollectionTransfer->getConcreteProducts() as $productConcreteTransfer) {
            foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
                if ($productConcreteTransfer->getIdProduct() === $productImageSetTransfer->getIdProduct()) {
                    $productConcreteTransfer->addImageSet($productImageSetTransfer);

                    continue;
                }
            }
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteDataCollectionTransfer
     *
     * @return int[]
     */
    protected function extractProductConcreteIds(ProductConcreteCollectionTransfer $productConcreteDataCollectionTransfer): array
    {
        $productConcreteIds = array_map(function (ProductConcreteTransfer $productConcreteTransfer) {
            return $productConcreteTransfer->getIdProduct() ?? null;
        }, $productConcreteDataCollectionTransfer->getConcreteProducts()->getArrayCopy());

        $productConcreteIds = array_filter($productConcreteIds);

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function transformProductConcreteNames(ProductConcreteCollectionTransfer $productConcreteCollectionTransfer): ProductConcreteCollectionTransfer
    {
        foreach ($productConcreteCollectionTransfer->getConcreteProducts() as $productConcreteTransfer) {
            $extendedProductConcreteName = $this->buildExtendedProductConcreteName($productConcreteTransfer);
            $productConcreteTransfer->setName($extendedProductConcreteName);
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    protected function buildExtendedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $productConcreteName = $productConcreteTransfer->getName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];

        foreach ($productConcreteTransfer->getAttributes() as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        return implode(static::EXTENDED_PRODUCT_CONCRETE_NAME_SEPARATOR, $extendedProductConcreteNameParts);
    }
}
