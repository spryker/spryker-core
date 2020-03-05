<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Dependency\Facade;

class ProductOfferGuiPageToProductImageFacadeBridge implements ProductOfferGuiPageToProductImageFacadeInterface
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
     * @param int[] $productIds
     * @param int $localeId
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImageSetsByProductConcreteIdsAndLocaleId(array $productIds, int $localeId): array
    {
        return $this->productImageFacade->getProductImageSetsByProductConcreteIdsAndLocaleId($productIds, $localeId);
    }
}
