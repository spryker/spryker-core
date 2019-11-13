<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Expander;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductImageFacadeInterface;

class ProductConcretePageSearchExpander implements ProductConcretePageSearchExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductImageFacadeInterface $productImageFacade
     */
    public function __construct(ProductPageSearchToProductImageFacadeInterface $productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function expandProductConcretePageSearchTransferWithProductImages(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        $images = [];
        $localizedProductImageSets = $this->getLocalizedProductImageSets($productConcretePageSearchTransfer);

        foreach ($localizedProductImageSets as $productImageSetTransfer) {
            $images = array_merge($images, $this->mapImageSetTransferToImages($productImageSetTransfer));
        }

        $productConcretePageSearchTransfer->setProductImages($images);

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getLocalizedProductImageSets(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): array
    {
        $productConcretePageSearchTransfer
            ->requireFkProduct()
            ->requireLocale();

        $localizedProductImageSets = [];
        $productImageSetTransfers = $this->productImageFacade
            ->getProductImagesSetCollectionByProductId($productConcretePageSearchTransfer->getFkProduct());

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            if (!$productImageSetTransfer->getLocale()
                || $productImageSetTransfer->getLocale()->getLocaleName() !== $productConcretePageSearchTransfer->getLocale()) {
                continue;
            }

            $localizedProductImageSets[] = $productImageSetTransfer;
        }

        return $localizedProductImageSets;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return array
     */
    protected function mapImageSetTransferToImages(ProductImageSetTransfer $productImageSetTransfer): array
    {
        $images = [];

        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $images[] = $productImageTransfer->toArray(false, true);
        }

        return $images;
    }
}
