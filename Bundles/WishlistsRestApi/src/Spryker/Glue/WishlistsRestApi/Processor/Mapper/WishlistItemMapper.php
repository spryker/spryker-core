<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

class WishlistItemMapper implements WishlistItemMapperInterface
{
    /**
     * @var array<\Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface>
     */
    protected $restWishlistItemsAttributesMapperPlugins;

    /**
     * @param array<\Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface> $restWishlistItemsAttributesMapperPlugins
     */
    public function __construct(array $restWishlistItemsAttributesMapperPlugins = [])
    {
        $this->restWishlistItemsAttributesMapperPlugins = $restWishlistItemsAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer
     */
    public function mapWishlistItemTransferToRestWishlistItemsAttributes(
        WishlistItemTransfer $wishlistItemTransfer,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestWishlistItemsAttributesTransfer {
        $restWishlistItemsAttributesTransfer->fromArray($wishlistItemTransfer->toArray(), true);
        $restWishlistItemsAttributesTransfer->setId($wishlistItemTransfer->getSku());
        $restWishlistItemsAttributesTransfer = $this->executeRestWishlistItemsAttributesMapperPlugins(
            $wishlistItemTransfer,
            $restWishlistItemsAttributesTransfer,
        );

        return $restWishlistItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer
     */
    protected function executeRestWishlistItemsAttributesMapperPlugins(
        WishlistItemTransfer $wishlistItemTransfer,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestWishlistItemsAttributesTransfer {
        foreach ($this->restWishlistItemsAttributesMapperPlugins as $restWishlistItemsAttributesMapperPlugin) {
            $restWishlistItemsAttributesTransfer = $restWishlistItemsAttributesMapperPlugin->map(
                $wishlistItemTransfer,
                $restWishlistItemsAttributesTransfer,
            );
        }

        return $restWishlistItemsAttributesTransfer;
    }
}
