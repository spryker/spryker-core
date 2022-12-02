<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\WishlistItem;

use ArrayObject;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistItemUpdater implements WishlistItemUpdaterInterface
{
    /**
     * @var \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @var array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesUpdateStrategyPluginInterface>
     */
    protected $restWishlistItemsAttributesUpdateStrategyPlugins;

    /**
     * @param \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface $wishlistFacade
     * @param array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesUpdateStrategyPluginInterface> $restWishlistItemsAttributesUpdateStrategyPlugins
     */
    public function __construct(
        WishlistsRestApiToWishlistFacadeInterface $wishlistFacade,
        array $restWishlistItemsAttributesUpdateStrategyPlugins
    ) {
        $this->wishlistFacade = $wishlistFacade;
        $this->restWishlistItemsAttributesUpdateStrategyPlugins = $restWishlistItemsAttributesUpdateStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        $this->assertRequiredAttributes($wishlistItemRequestTransfer);

        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter(
            $this->createWishlistFilterTransfer($wishlistItemRequestTransfer),
        );

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->createWishlistNotFoundErrorResponse($wishlistResponseTransfer);
        }

        $wishlistTransfer = $wishlistResponseTransfer->getWishlistOrFail();
        $wishlistItemResponseTransfer = $this->executeRestWishlistItemsAttributesUpdateStrategyPlugins(
            $wishlistItemRequestTransfer,
            $wishlistTransfer->getWishlistItems(),
        );

        if ($wishlistItemResponseTransfer) {
            return $wishlistItemResponseTransfer;
        }

        $originalWishlistItemTransfer = $this->findWishlistItemInWishlist($wishlistTransfer, $wishlistItemRequestTransfer);

        if (!$originalWishlistItemTransfer) {
            return $this->createWishlistItemNotFoundResponse();
        }

        $sku = $wishlistItemRequestTransfer->getSku() ?? $originalWishlistItemTransfer->getSku();

        $wishlistItemTransfer = $originalWishlistItemTransfer->fromArray($wishlistItemRequestTransfer->toArray(), true)
            ->setSku($sku);

        return $this->wishlistFacade->updateWishlistItem($wishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(WishlistItemRequestTransfer $wishlistItemRequestTransfer): void
    {
        $wishlistItemRequestTransfer
            ->requireIdCustomer()
            ->requireUuidWishlist()
            ->requireUuid();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    protected function findWishlistItemInWishlist(
        WishlistTransfer $wishlistTransfer,
        WishlistItemRequestTransfer $wishlistItemRequestTransfer
    ): ?WishlistItemTransfer {
        if (!$wishlistTransfer->getWishlistItems()->count()) {
            return null;
        }

        foreach ($wishlistTransfer->getWishlistItems() as $wishlistItemTransfer) {
            if ($wishlistItemTransfer->getSku() === $wishlistItemRequestTransfer->getUuid()) {
                return $wishlistItemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer|null
     */
    protected function executeRestWishlistItemsAttributesUpdateStrategyPlugins(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): ?WishlistItemResponseTransfer {
        foreach ($this->restWishlistItemsAttributesUpdateStrategyPlugins as $restWishlistItemsAttributesUpdateStrategyPlugin) {
            if (!$restWishlistItemsAttributesUpdateStrategyPlugin->isApplicable($wishlistItemRequestTransfer, $wishlistItemTransfers)) {
                continue;
            }

            return $restWishlistItemsAttributesUpdateStrategyPlugin->update(
                $wishlistItemRequestTransfer,
                $wishlistItemTransfers,
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistFilterTransfer
     */
    protected function createWishlistFilterTransfer(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistFilterTransfer
    {
        return (new WishlistFilterTransfer())
            ->fromArray($wishlistItemRequestTransfer->toArray(), true)
            ->setUuid($wishlistItemRequestTransfer->getUuidWishlist());
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
    protected function createWishlistItemNotFoundResponse(): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST);
    }
}
