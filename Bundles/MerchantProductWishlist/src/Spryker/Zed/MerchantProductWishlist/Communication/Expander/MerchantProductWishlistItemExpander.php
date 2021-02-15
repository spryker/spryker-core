<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductWishlist\Communication\Expander;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToProductFacadeInterface;

class MerchantProductWishlistItemExpander implements MerchantProductWishlistItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @param \Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToMerchantProductFacadeInterface $merchantProductFacade
     */
    public function __construct(
        MerchantProductWishlistToProductFacadeInterface $productFacade,
        MerchantProductWishlistToMerchantProductFacadeInterface $merchantProductFacade
    ) {
        $this->productFacade = $productFacade;
        $this->merchantProductFacade = $merchantProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        if (!$wishlistItemTransfer->getSku()) {
            return $wishlistItemTransfer;
        }

        /** @var string $sku */
        $sku = $wishlistItemTransfer->getSku();

        /** @var int $idProductAbstract */
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku($sku);

        if (!$idProductAbstract) {
            return $wishlistItemTransfer;
        }

        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->setIdProductAbstract($idProductAbstract);

        $merchantTransfer = $this->merchantProductFacade
            ->findMerchant($merchantProductCriteriaTransfer);

        if (!$merchantTransfer) {
            return $wishlistItemTransfer;
        }

        return $wishlistItemTransfer->setMerchantReference($merchantTransfer->getMerchantReference());
    }
}
