<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Expander;

use ArrayObject;
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
        $productConcretePageSearchTransfer
            ->requireFkProduct()
            ->requireLocale();

        $images = [];

        $productImageSetTransfers = $this->productImageFacade->getProductImagesSetCollectionByProductId($productConcretePageSearchTransfer->getFkProduct());
        $productImageSetTransfers = $this->productImageFacade->resolveProductImageSetsForLocale(
            new ArrayObject($productImageSetTransfers),
            $productConcretePageSearchTransfer->getLocale()
        );

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $images = array_merge($images, $this->mapImageSetTransferToImages($productImageSetTransfer));
        }

        $productConcretePageSearchTransfer->setImages($images);

        return $productConcretePageSearchTransfer;
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
