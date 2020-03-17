<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface;

class ProductTableDataImageHydrator implements ProductTableDataHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface $productImageFacade
     */
    public function __construct(ProductOfferGuiPageToProductImageFacadeInterface $productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * Hydrates concrete product transfers from the collection with image data.
     *
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function hydrateProductTableData(
        ProductTableDataTransfer $productTableDataTransfer,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): ProductTableDataTransfer {
        $localeTransfer = $productTableCriteriaTransfer->requireLocale()->getLocale();
        $productImageSetCollectionTransfer = $this->findProductConcreteProductImageSetsForLocale($productTableDataTransfer);
        $productTableDataTransfer = $this->addProductImagesToProductTableData(
            $productTableDataTransfer,
            $productImageSetCollectionTransfer,
            $localeTransfer
        );

        return $productTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    protected function findProductConcreteProductImageSetsForLocale(ProductTableDataTransfer $productTableDataTransfer): ProductImageSetCollectionTransfer
    {
        $productImageSetCriteriaTransfer = new ProductImageSetCriteriaTransfer();
        $productImageSetCriteriaTransfer->setProductConcreteIds(
            $this->extractProductConcreteIds($productTableDataTransfer)
        );

        return $this->productImageFacade->getProductImageSets($productImageSetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    protected function addProductImagesToProductTableData(
        ProductTableDataTransfer $productTableDataTransfer,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer,
        LocaleTransfer $localeTransfer
    ): ProductTableDataTransfer {
        foreach ($productTableDataTransfer->getRows() as $productTableRowDataTransfer) {
            foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
                if ($productTableRowDataTransfer->getIdProduct() !== $productImageSetTransfer->getIdProduct()) {
                    continue;
                }

                $productImageSetLocaleId = $productImageSetTransfer->getIdLocale();

                if (!$productImageSetLocaleId && $productTableRowDataTransfer->getImage()) {
                    continue;
                }

                if ($localeTransfer->getIdLocale() !== $productImageSetLocaleId) {
                    continue;
                }

                $productTableRowDataTransfer->setImage(
                    $this->getFirstImageFromImageSet($productImageSetTransfer)
                );
            }
        }

        return $productTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return string|null
     */
    protected function getFirstImageFromImageSet(ProductImageSetTransfer $productImageSetTransfer): ?string
    {
        if (!$productImageSetTransfer->getProductImages()->count()) {
            return null;
        }

        return $productImageSetTransfer->getProductImages()[0]->getExternalUrlSmall();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     *
     * @return int[]
     */
    protected function extractProductConcreteIds(ProductTableDataTransfer $productTableDataTransfer): array
    {
        $productConcreteIds = array_map(function (ProductTableRowDataTransfer $productTableRowDataTransfer) {
            return $productTableRowDataTransfer->getIdProduct() ?? null;
        }, $productTableDataTransfer->getRows()->getArrayCopy());

        $productConcreteIds = array_filter($productConcreteIds);

        return $productConcreteIds;
    }
}
