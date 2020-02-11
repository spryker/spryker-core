<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\Wishlist;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistUpdater implements WishlistUpdaterInterface
{
    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::ERROR_MESSAGE_NAME_ALREADY_EXISTS
     */
    protected const ERROR_MESSAGE_NAME_ALREADY_EXISTS = 'wishlist.validation.error.name.already_exists';

    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT
     */
    protected const ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT = 'wishlist.validation.error.name.wrong_format';

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
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function updateWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        $wishlistResponseTransfer = $this->wishlistFacade
            ->getWishlistByFilter($this->createWishlistFilterTransfer($wishlistRequestTransfer));

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            $wishlistResponseTransfer->setErrorIdentifier(
                WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
            );

            return $wishlistResponseTransfer;
        }

        $originalWishlist = $wishlistResponseTransfer->getWishlist();
        $updatedWishlist = $wishlistRequestTransfer->getWishlist();
        $wishlistTransfer = $originalWishlist->fromArray($updatedWishlist->modifiedToArray(), true);

        $wishlistResponseTransfer = $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->createWishlistResponseTransferWithError($wishlistResponseTransfer);
        }

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    protected function createWishlistResponseTransferWithError(WishlistResponseTransfer $wishlistResponseTransfer): WishlistResponseTransfer
    {
        foreach ($wishlistResponseTransfer->getErrors() as $error) {
            if ($error === static::ERROR_MESSAGE_NAME_ALREADY_EXISTS) {
                return $wishlistResponseTransfer->setErrorIdentifier(
                    WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_ALREADY_EXIST
                );
            }
            if ($error === static::ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT) {
                return $wishlistResponseTransfer->setErrorIdentifier(
                    WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT
                );
            }
        }

        return $wishlistResponseTransfer->setErrorIdentifier(
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_CANT_BE_UPDATED
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistFilterTransfer
     */
    protected function createWishlistFilterTransfer(WishlistRequestTransfer $wishlistRequestTransfer): WishlistFilterTransfer
    {
        return (new WishlistFilterTransfer())->fromArray($wishlistRequestTransfer->toArray(), true);
    }
}
