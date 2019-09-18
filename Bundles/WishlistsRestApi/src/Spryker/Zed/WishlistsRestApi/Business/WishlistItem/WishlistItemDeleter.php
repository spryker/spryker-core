<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\WishlistItem;

use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistItemDeleter implements WishlistItemDeleterInterface
{
    /**
     * @var \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @param \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface $wishlistFacade
     */
    public function __construct(WishlistsRestApiToWishlistFacadeInterface $wishlistFacade)
    {
        $this->wishlistFacade = $wishlistFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function delete(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        $wishlistItemRequestTransfer->requireIdCustomer()
            ->requireUuidWishlist()
            ->requireSku();

        $wishlistRequestTransfer = $this->createWishlistRequestTransfer($wishlistItemRequestTransfer);

        $wishlistResponseTransfer = $this->wishlistFacade->getCustomerWishlistByUuid($wishlistRequestTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->createWishlistNotFoundErrorResponse($wishlistResponseTransfer);
        }

        if (!$this->isItemInWishlist($wishlistResponseTransfer->getWishlist(), $wishlistItemRequestTransfer->getSku())) {
            return $this->createWishlistItemNotFoundErrorResponse();
        }

        $wishlistItemTransfer = $this->createWishlistItemTransfer(
            $wishlistResponseTransfer->getWishlist(),
            $wishlistItemRequestTransfer
        );
        $this->wishlistFacade->removeItem($wishlistItemTransfer);

        return $this->createSuccessResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistNotFoundErrorResponse(WishlistResponseTransfer $wishlistResponseTransfer): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->setErrors($wishlistResponseTransfer->getErrors())
            ->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createWishlistItemTransfer(
        WishlistTransfer $wishlistTransfer,
        WishlistItemRequestTransfer $wishlistItemRequestTransfer
    ): WishlistItemTransfer {
        return (new WishlistItemTransfer())
            ->setSku($wishlistItemRequestTransfer->getSku())
            ->setFkCustomer($wishlistItemRequestTransfer->getIdCustomer())
            ->setFkWishlist($wishlistTransfer->getIdWishlist())
            ->setWishlistName($wishlistTransfer->getName());
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createSuccessResponse()
    {
        return (new WishlistItemResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistRequestTransfer
     */
    protected function createWishlistRequestTransfer(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistRequestTransfer
    {
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setUuid($wishlistItemRequestTransfer->getUuidWishlist())
            ->setIdCustomer($wishlistItemRequestTransfer->getIdCustomer());

        return $wishlistRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistItemNotFoundErrorResponse(): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param string $sku
     *
     * @return bool
     */
    protected function isItemInWishlist(WishlistTransfer $wishlistTransfer, string $sku): bool
    {
        if (!$wishlistTransfer->getWishlistItems()->count()) {
            return false;
        }

        foreach ($wishlistTransfer->getWishlistItems() as $wishlistItemTransfer) {
            if ($wishlistItemTransfer->getSku() === $sku) {
                return true;
            }
        }

        return false;
    }
}
