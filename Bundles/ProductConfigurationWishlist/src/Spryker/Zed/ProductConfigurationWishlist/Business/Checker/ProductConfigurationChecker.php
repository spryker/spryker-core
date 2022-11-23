<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Business\Checker;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Spryker\Zed\ProductConfigurationWishlist\Dependency\Facade\ProductConfigurationWishlistToProductConfigurationFacadeInterface;

class ProductConfigurationChecker implements ProductConfigurationCheckerInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationWishlist\Dependency\Facade\ProductConfigurationWishlistToProductConfigurationFacadeInterface
     */
    protected $productConfigurationFacade;

    /**
     * @param \Spryker\Zed\ProductConfigurationWishlist\Dependency\Facade\ProductConfigurationWishlistToProductConfigurationFacadeInterface $productConfigurationFacade
     */
    public function __construct(ProductConfigurationWishlistToProductConfigurationFacadeInterface $productConfigurationFacade)
    {
        $this->productConfigurationFacade = $productConfigurationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductConfiguration(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer {
        return (new WishlistPreAddItemCheckResponseTransfer())
            ->setIsSuccess($this->hasProductConfiguration($wishlistItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductConfiguration(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer {
        return (new WishlistPreUpdateItemCheckResponseTransfer())
            ->setIsSuccess($this->hasProductConfiguration($wishlistItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return bool
     */
    protected function hasProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): bool
    {
        if (!$wishlistItemTransfer->getProductConfigurationInstance()) {
            return true;
        }

        return (bool)$this->getProductConfigurationCollection($wishlistItemTransfer)
            ->getProductConfigurations()
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    protected function getProductConfigurationCollection(WishlistItemTransfer $wishlistItemTransfer): ProductConfigurationCollectionTransfer
    {
        $productConfigurationConditionsTransfer = (new ProductConfigurationConditionsTransfer())->addSku($wishlistItemTransfer->getSkuOrFail());
        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaTransfer())->setProductConfigurationConditions($productConfigurationConditionsTransfer);

        return $this->productConfigurationFacade->getProductConfigurationCollection($productConfigurationCriteriaTransfer);
    }
}
