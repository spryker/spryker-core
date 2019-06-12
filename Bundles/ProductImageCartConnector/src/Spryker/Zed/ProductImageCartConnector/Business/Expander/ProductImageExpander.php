<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\ProductImageCartConnector\ProductImageCartConnectorConfig;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageInterface;

class ProductImageExpander implements ProductImageExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageInterface $productImageFacade
     */
    public function __construct(
        ProductImageCartConnectorToProductImageInterface $productImageFacade
    ) {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
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
        $imageSets = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $itemTransfer->getId()
        );

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
            if ($imageSet->getName() === ProductImageCartConnectorConfig::DEFAULT_IMAGE_SET_NAME) {
                return $imageSet->getProductImages();
            }
        }

        return $imageSets[0]->getProductImages();
    }
}
