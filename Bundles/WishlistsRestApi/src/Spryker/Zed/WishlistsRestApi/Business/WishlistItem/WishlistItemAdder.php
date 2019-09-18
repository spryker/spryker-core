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

class WishlistItemAdder implements WishlistItemAdderInterface
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
    public function addWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        $wishlistItemRequestTransfer->requireIdCustomer()
            ->requireUuidWishlist()
            ->requireSku();

        $wishlistRequestTransfer = $this->createWishlistRequest($wishlistItemRequestTransfer);
        $wishlistResponseTransfer = $this->wishlistFacade->getCustomerWishlistByUuid($wishlistRequestTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->createWishlistNotFoundErrorResponse($wishlistResponseTransfer);
        }

        $wishlistItemTransfer = $this->createWishlistItemTransfer(
            $wishlistResponseTransfer->getWishlist(),
            $wishlistItemRequestTransfer
        );

        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);
        if (!$wishlistItemTransfer->getIdWishlistItem()) {
            return $this->createWishlistItemCanNotBeAddedError();
        }

        return $this->createWishlistItemSuccessResponse($wishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistRequestTransfer
     */
    protected function createWishlistRequest(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistRequestTransfer
    {
        return (new WishlistRequestTransfer())
            ->setIdCustomer($wishlistItemRequestTransfer->getIdCustomer())
            ->setUuid($wishlistItemRequestTransfer->getUuidWishlist());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createWishlistItemTransfer(WishlistTransfer $wishlistTransfer, WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemTransfer
    {
        $wishlistItemTransfer = new WishlistItemTransfer();
        $wishlistItemTransfer->setFkWishlist($wishlistTransfer->getIdWishlist());
        $wishlistItemTransfer->setWishlistName($wishlistTransfer->getName());
        $wishlistItemTransfer->setFkCustomer($wishlistTransfer->getFkCustomer());

        $wishlistItemTransfer->fromArray(
            $wishlistItemRequestTransfer->toArray(),
            true
        );

        return $wishlistItemTransfer;
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
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistItemCanNotBeAddedError(): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_ITEM_CANT_BE_ADDED);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistItemSuccessResponse(WishlistItemTransfer $wishlistItemTransfer): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(true)
            ->setWishlistItem($wishlistItemTransfer);
    }
}
