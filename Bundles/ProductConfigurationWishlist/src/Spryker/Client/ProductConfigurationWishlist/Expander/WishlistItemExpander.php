<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface;

class WishlistItemExpander implements WishlistItemExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_PRODUCT_CONFIGURATION_MARK = 'has_product_configuration_attached';

    /**
     * @var string
     */
    protected const PARAM_PRODUCT_CONFIGURATION_MARK_VALUE = '1';

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface $wishlistClient
     */
    public function __construct(
        ProductConfigurationWishlistToProductConfigurationStorageClientInterface $productConfigurationStorageClient,
        ProductConfigurationWishlistToWishlistClientInterface $wishlistClient
    ) {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
        $this->wishlistClient = $wishlistClient;
    }

    /**
     * @phpstan-param array<string, mixed> $params
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer
    {
        $productConfigurationMark = $params[static::PARAM_PRODUCT_CONFIGURATION_MARK] ?? null;

        if ($productConfigurationMark === static::PARAM_PRODUCT_CONFIGURATION_MARK_VALUE) {
            return $this->setProductConfigurationByIdWishlistItem($wishlistItemTransfer);
        }

        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
            ->findProductConfigurationInstanceBySku($wishlistItemTransfer->getSkuOrFail());

        return $wishlistItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function setProductConfigurationByIdWishlistItem(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistItemTransfer {
        $idWishlistItem = $wishlistItemTransfer->getIdWishlistItem();

        if (!$idWishlistItem) {
            return $wishlistItemTransfer;
        }

        $wishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())->setIdWishlistItem($idWishlistItem);
        $wishlistItemResponseTransfer = $this->wishlistClient->getWishlistItem($wishlistItemCriteriaTransfer);

        if (!$wishlistItemResponseTransfer->getIsSuccess()) {
            return $wishlistItemTransfer;
        }

        return $wishlistItemTransfer->setProductConfigurationInstance(
            $wishlistItemResponseTransfer->getWishlistItemOrFail()->getProductConfigurationInstance(),
        );
    }
}
