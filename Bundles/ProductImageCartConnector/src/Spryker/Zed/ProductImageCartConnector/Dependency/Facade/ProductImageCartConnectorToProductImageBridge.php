<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Dependency\Facade;

class ProductImageCartConnectorToProductImageBridge implements ProductImageCartConnectorToProductImageInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface $productImageFacade
     */
    public function __construct($productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param int $productId
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($productId)
    {
        return $this->productImageFacade->getProductImagesSetCollectionByProductId($productId);
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getDefaultProductImagesByProductIds(array $productIds): array
    {
        return $this->productImageFacade->getDefaultProductImagesByProductIds($productIds);
    }
}
