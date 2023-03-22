<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageFacadeInterface;

class ProductBundleImageCartExpander implements ProductBundleCartExpanderInterface
{
    /**
     * @var string
     */
    public const DEFAULT_IMAGE_SET_NAME = 'default';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageFacadeInterface $productImageFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductBundleToProductImageFacadeInterface $productImageFacade,
        ProductBundleToLocaleFacadeInterface $localeFacade
    ) {
        $this->productImageFacade = $productImageFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItems(CartChangeTransfer $cartChangeTransfer)
    {
        $currentLocaleId = $this->localeFacade->getCurrentLocale()->getIdLocale();

        $productImageTransfersIndexedByItemId = [];
        foreach ($cartChangeTransfer->getQuote()->getBundleItems() as $itemTransfer) {
            if (array_key_exists($itemTransfer->getId(), $productImageTransfersIndexedByItemId)) {
                $itemTransfer->setImages($productImageTransfersIndexedByItemId[$itemTransfer->getId()]);

                continue;
            }

            $imageSets = $this->productImageFacade->getCombinedConcreteImageSets(
                $itemTransfer->getId(),
                $itemTransfer->getIdProductAbstract(),
                $currentLocaleId,
            );

            $productImages = $this->getProductImages($imageSets);

            $itemTransfer->setImages($productImages);
            $productImageTransfersIndexedByItemId[$itemTransfer->getId()] = $productImages;
        }

        return $cartChangeTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductImageSetTransfer> $imageSets
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageTransfer>
     */
    protected function getProductImages(array $imageSets): ArrayObject
    {
        foreach ($imageSets as $imageSet) {
            if ($imageSet->getName() === static::DEFAULT_IMAGE_SET_NAME) {
                return $imageSet->getProductImages();
            }

            return $imageSet->getProductImages();
        }

        return new ArrayObject();
    }
}
