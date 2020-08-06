<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageFilterTransfer;

class ProductPageSearchToProductImageFacadeBridge implements ProductPageSearchToProductImageFacadeInterface
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
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct)
    {
        return $this->productImageFacade->getProductImagesSetCollectionByProductId($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageFilterTransfer $productImageFilterTransfer
     *
     * @return int[]
     */
    public function getProductConcreteIds(ProductImageFilterTransfer $productImageFilterTransfer): array
    {
        return $this->productImageFacade->getProductConcreteIds($productImageFilterTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     * @param string $localeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function resolveProductImageSetsForLocale(ArrayObject $productImageSetTransfers, string $localeName): ArrayObject
    {
        return $this->productImageFacade->resolveProductImageSetsForLocale($productImageSetTransfers, $localeName);
    }
}
