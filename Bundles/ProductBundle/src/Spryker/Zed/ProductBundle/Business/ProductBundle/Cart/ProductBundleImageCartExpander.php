<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageInterface;

class ProductBundleImageCartExpander implements ProductBundleCartExpanderInterface
{

    const DEFAULT_IMAGE_SET_NAME = 'default';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageInterface $productImageFacade
     */
    public function __construct(ProductBundleToProductImageInterface $productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getQuote()->getBundleItems() as $itemTransfer) {
            $this->expandItemsWithImages($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandItemsWithImages(ItemTransfer $itemTransfer)
    {
        $imageSets = $this->productImageFacade->getProductImagesSetCollectionByProductId($itemTransfer->getId());

        if (!$imageSets) {
            return;
        }

        $itemTransfer->setImages($this->getProductImages($imageSets));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $imageSets
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductImageTransfer[]
     */
    protected function getProductImages(array $imageSets)
    {
        foreach ($imageSets as $imageSet) {
            if ($imageSet->getName() === static::DEFAULT_IMAGE_SET_NAME) {
                return $imageSet->getProductImages();
            }
        }

        return $imageSets[0]->getProductImages();
    }

}
