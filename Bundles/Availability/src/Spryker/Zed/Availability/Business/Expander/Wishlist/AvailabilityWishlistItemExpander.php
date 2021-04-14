<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Expander\Wishlist;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;

class AvailabilityWishlistItemExpander implements AvailabilityWishlistItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface
     */
    protected $productAvailabilityReader;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected $sellable;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface $productAvailabilityReader
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductAvailabilityReaderInterface $productAvailabilityReader,
        SellableInterface $sellable,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        $this->productAvailabilityReader = $productAvailabilityReader;
        $this->sellable = $sellable;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithAvailability(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $productConcreteAvailabilityTransfer = $this->findProductConcreteAvailability($wishlistItemTransfer);

        if (!$productConcreteAvailabilityTransfer) {
            return $wishlistItemTransfer;
        }

        return $wishlistItemTransfer->setProductConcreteAvailability($productConcreteAvailabilityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithSellable(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $productConcreteAvailabilityTransfer = $this->findProductConcreteAvailability($wishlistItemTransfer);

        if (!$productConcreteAvailabilityTransfer) {
            return $wishlistItemTransfer;
        }

        /** @var \Spryker\DecimalObject\Decimal $availability */
        $availability = $productConcreteAvailabilityTransfer->getAvailability();
        /** @var string $sku */
        $sku = $wishlistItemTransfer->requireSku()->getSku();
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->fromArray($wishlistItemTransfer->toArray(), true);
        $isWishlistItemSellable = $this->sellable->isProductSellableForStore(
            $sku,
            $availability,
            $this->storeFacade->getCurrentStore(),
            $productAvailabilityCriteriaTransfer
        );

        return $wishlistItemTransfer->setIsSellable($isWishlistItemSellable);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function findProductConcreteAvailability(WishlistItemTransfer $wishlistItemTransfer): ?ProductConcreteAvailabilityTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->fromArray($wishlistItemTransfer->toArray(), true);

        /** @var string $sku */
        $sku = $wishlistItemTransfer->requireSku()->getSku();

        return $this->productAvailabilityReader->findOrCreateProductConcreteAvailabilityBySkuForStore(
            $sku,
            $storeTransfer,
            $productAvailabilityCriteriaTransfer
        );
    }
}
