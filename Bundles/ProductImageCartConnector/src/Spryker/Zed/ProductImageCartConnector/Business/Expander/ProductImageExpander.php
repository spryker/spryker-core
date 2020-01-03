<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Business\Expander;

use ArrayObject;
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
        $productIds = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getId();
        }, $cartChangeTransfer->getItems()->getArrayCopy());
        $productImages = $this->productImageFacade->getProductImagesByProductIdsAndProductImageSetName($productIds, ProductImageCartConnectorConfig::DEFAULT_IMAGE_SET_NAME);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->expandItemsWithImages($itemTransfer, $productImages);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductImageTransfer[][] $productImages
     *
     * @return void
     */
    protected function expandItemsWithImages(ItemTransfer $itemTransfer, array $productImages): void
    {
        if (!isset($productImages[$itemTransfer->getId()])) {
            return;
        }

        $itemTransfer->setImages(new ArrayObject($productImages[$itemTransfer->getId()]));
    }
}
