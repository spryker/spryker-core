<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\WishlistCheck;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class WishlistAddItemPreCheck implements WishlistAddItemPreCheckInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     */
    public function __construct(ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository)
    {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductIsNotDiscontinued(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new WishlistPreAddItemCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        if ($this->isProductDiscontinued($wishlistItemTransfer->getSku())) {
            $cartPreCheckResponseTransfer->setIsSuccess(false);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function isProductDiscontinued(string $sku): bool
    {
        return $this->productDiscontinuedRepository->checkIfProductDiscontinuedBySku($sku);
    }
}
