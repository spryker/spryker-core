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
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistItemAdder implements WishlistItemAdderInterface
{
    //TODO: can we do so?
    protected const ERROR_MESSAGE_WISHLIST_NOT_FOUND = 'error.message.wishlist.not.found';
    protected const ERROR_MESSAGE_WISHLIST_ITEM_CAN_NOT_BE_ADDED = 'error.message.wishlist.item.can.not.be.added';

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
    public function add(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        $wishlistRequestTransfer = $this->createWishlistRequest($wishlistItemRequestTransfer);
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByIdCustomerAndUuid($wishlistRequestTransfer);
        $wishlistTransfer = $wishlistResponseTransfer->getWishlist();

        if ($wishlistTransfer === null) {
            return $this->createWishlistNotFoundErrorResponse();
        }

        $wishlistItemTransfer = $this->createWishlistItemTransfer($wishlistTransfer);
        $wishlistItemTransfer->fromArray(
            $wishlistItemRequestTransfer->toArray(),
            true
        );

        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);
        if (!$wishlistItemTransfer->getIdWishlistItem()) {
            return $this->createWishlistItemCanNotBeAddedError();
        }

        return $this->createWishlistItemSuccessResponse($wishlistTransfer, $wishlistItemTransfer);
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
            ->setUuid($wishlistItemRequestTransfer->getIdWishlist());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param string|null $sku
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createWishlistItemTransfer(WishlistTransfer $wishlistTransfer, ?string $sku = null): WishlistItemTransfer
    {
        $wishlistItemTransfer = new WishlistItemTransfer();
        $wishlistItemTransfer->setFkWishlist($wishlistTransfer->getIdWishlist());
        $wishlistItemTransfer->setWishlistName($wishlistTransfer->getName());
        $wishlistItemTransfer->setFkCustomer($wishlistTransfer->getFkCustomer());
        $wishlistItemTransfer->setSku($sku);

        return $wishlistItemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistNotFoundErrorResponse(): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->addError(static::ERROR_MESSAGE_WISHLIST_NOT_FOUND);
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistItemCanNotBeAddedError(): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->addError(static::ERROR_MESSAGE_WISHLIST_ITEM_CAN_NOT_BE_ADDED);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createWishlistItemSuccessResponse(WishlistTransfer $wishlistTransfer, WishlistItemTransfer $wishlistItemTransfer): WishlistItemResponseTransfer
    {

        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByIdCustomerAndUuid(
            (new WishlistRequestTransfer())
                ->setIdCustomer($wishlistTransfer->getFkCustomer())
                ->setUuid($wishlistTransfer->getUuid())
        );

        //TODO: check if items re mapped
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(true)
            ->setWishlist($wishlistResponseTransfer->getWishlist())
            ->setAffectedWishlistItem($wishlistItemTransfer);
    }
}
