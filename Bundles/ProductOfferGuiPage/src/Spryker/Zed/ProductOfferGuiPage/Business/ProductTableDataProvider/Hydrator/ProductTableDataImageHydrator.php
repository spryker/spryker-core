<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
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
        $productImageSetCollectionTransfer = $this->findProductConcreteProductImageSetsForLocale(
            $productTableDataTransfer,
            $localeTransfer
        );
        $productTableDataTransfer = $this->mergeConcreteProductsWithProductImageSets(
            $productTableDataTransfer,
            $productImageSetCollectionTransfer
        );

        return $productTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    protected function findProductConcreteProductImageSetsForLocale(
        ProductTableDataTransfer $productTableDataTransfer,
        LocaleTransfer $localeTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetCriteriaTransfer = new ProductImageSetCriteriaTransfer();
        $productImageSetCriteriaTransfer->setProductConcreteIds(
            $this->extractProductConcreteIds($productTableDataTransfer)
        );
        $productImageSetCriteriaTransfer->setLocaleId($localeTransfer->requireIdLocale()->getIdLocale());

        return $this->productImageFacade->getProductImageSets($productImageSetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    protected function mergeConcreteProductsWithProductImageSets(
        ProductTableDataTransfer $productTableDataTransfer,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): ProductTableDataTransfer {
        foreach ($productTableDataTransfer->getConcreteProducts() as $productConcreteTransfer) {
            foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
                if ($productConcreteTransfer->getIdProduct() === $productImageSetTransfer->getIdProduct()) {
                    $productConcreteTransfer->addImageSet($productImageSetTransfer);

                    continue;
                }
            }
        }

        return $productTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productConcreteDataCollectionTransfer
     *
     * @return int[]
     */
    protected function extractProductConcreteIds(ProductTableDataTransfer $productConcreteDataCollectionTransfer): array
    {
        $productConcreteIds = array_map(function (ProductConcreteTransfer $productConcreteTransfer) {
            return $productConcreteTransfer->getIdProduct() ?? null;
        }, $productConcreteDataCollectionTransfer->getConcreteProducts()->getArrayCopy());

        $productConcreteIds = array_filter($productConcreteIds);

        return $productConcreteIds;
    }
}
