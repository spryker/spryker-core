<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Validator;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Spryker\Zed\Wishlist\Persistence\WishlistRepositoryInterface;

class WishlistItemValidator implements WishlistItemValidatorInterface
{
    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::DEFAULT_NAME
     *
     * @var string
     */
    protected const DEFAULT_NAME = 'default';

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistRepositoryInterface
     */
    protected WishlistRepositoryInterface $wishlistRepository;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistRepositoryInterface $wishlistRepository
     */
    public function __construct(WishlistRepositoryInterface $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function validateWishlistItemBeforeCreation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer {
        return (new WishlistPreAddItemCheckResponseTransfer())
            ->setIsSuccess(
                $wishlistItemTransfer->getWishlistNameOrFail() === static::DEFAULT_NAME
                || $wishlistItemTransfer->getWishlistNameOrFail() === ''
                || $this->hasExistingWishlist($wishlistItemTransfer),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return bool
     */
    protected function hasExistingWishlist(WishlistItemTransfer $wishlistItemTransfer): bool
    {
        return $this->wishlistRepository->findWishlistByFilter(
            (new WishlistFilterTransfer())
                ->setName($wishlistItemTransfer->getWishlistNameOrFail())
                ->setIdCustomer($wishlistItemTransfer->getFkCustomerOrFail()),
        ) !== null;
    }
}
