<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageFilterTransfer;

class ProductImageStorageToProductImageBridge implements ProductImageStorageToProductImageInterface
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
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getCombinedAbstractImageSets($idProductAbstract, $idLocale): array
    {
        return $this->productImageFacade->getCombinedAbstractImageSets($idProductAbstract, $idLocale);
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale): array
    {
        return $this->productImageFacade->getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageFilterTransfer $productImageFilterTransfer
     *
     * @return array<int>
     */
    public function getProductConcreteIds(ProductImageFilterTransfer $productImageFilterTransfer): array
    {
        return $this->productImageFacade->getProductConcreteIds($productImageFilterTransfer);
    }
}
