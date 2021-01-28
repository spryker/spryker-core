<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Expander;

use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;

class ProductViewOfferAvailabilityExpander implements ProductViewOfferAvailabilityExpanderInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface $productOfferStorageReader
     */
    public function __construct(ProductOfferStorageReaderInterface $productOfferStorageReader)
    {
        $this->productOfferStorageReader = $productOfferStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        if (!$productStorageCriteriaTransfer || !$productViewTransfer->getProductOfferReference()) {
            return $productViewTransfer;
        }

        if (!$productViewTransfer->getAvailable()) {
            return $productViewTransfer;
        }

        $productOfferStorage = $this->productOfferStorageReader
            ->findProductOfferStorageByReference($productViewTransfer->getProductOfferReference());

        $isProductOfferAvailiable = $productOfferStorage !== null;

        return $productViewTransfer->setAvailable($isProductOfferAvailiable);
    }
}
