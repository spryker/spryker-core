<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
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
    protected ProductConfigurationWishlistToProductConfigurationStorageClientInterface $productConfigurationStorageClient;

    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface
     */
    protected ProductConfigurationWishlistToWishlistClientInterface $wishlistClient;

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
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer
    {
        $productConfigurationMark = $params[static::PARAM_PRODUCT_CONFIGURATION_MARK] ?? null;

        if ($productConfigurationMark === static::PARAM_PRODUCT_CONFIGURATION_MARK_VALUE) {
            return $this->setProductConfigurationByIdWishlistItem($wishlistItemTransfer);
        }

        $productConfigurationInstanceTransfer = $this->findProductConfigurationInstance($wishlistItemTransfer);

        return $wishlistItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findProductConfigurationInstance(
        WishlistItemTransfer $wishlistItemTransfer
    ): ?ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceCollectionTransfer = $this->getProductConfigurationInstanceCollection($wishlistItemTransfer);

        if (!$productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()->count()) {
            return null;
        }

        return $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()
            ->getIterator()
            ->current();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    protected function getProductConfigurationInstanceCollection(
        WishlistItemTransfer $wishlistItemTransfer
    ): ProductConfigurationInstanceCollectionTransfer {
        $productConfigurationInstanceConditionsTransfer = (new ProductConfigurationInstanceConditionsTransfer())
            ->addSku($wishlistItemTransfer->getSkuOrFail());

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaTransfer())
            ->setProductConfigurationInstanceConditions($productConfigurationInstanceConditionsTransfer);

        return $this->productConfigurationStorageClient
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);
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
