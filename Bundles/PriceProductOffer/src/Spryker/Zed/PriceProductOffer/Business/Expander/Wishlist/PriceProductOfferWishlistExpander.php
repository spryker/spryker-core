<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Expander\Wishlist;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\PriceProductOffer\Business\Reader\PriceProductOfferReaderInterface;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface;

class PriceProductOfferWishlistExpander implements PriceProductOfferWishlistExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\Reader\PriceProductOfferReaderInterface
     */
    protected $priceProductOfferReader;

    /**
     * @var \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\Reader\PriceProductOfferReaderInterface $priceProductOfferReader
     * @param \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        PriceProductOfferReaderInterface $priceProductOfferReader,
        PriceProductOfferToStoreFacadeInterface $storeFacade
    ) {
        $this->priceProductOfferReader = $priceProductOfferReader;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithPrices(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        if (!$wishlistItemTransfer->getProductOfferReference()) {
            return $wishlistItemTransfer;
        }

        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($wishlistItemTransfer->getProductOfferReference());

        /** @var int $idStore */
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();
        $priceProductOfferCriteriaTransfer = (new PriceProductOfferCriteriaTransfer())
            ->setProductOfferCriteriaFilter($productOfferCriteriaFilterTransfer)
            ->addIdStore($idStore);

        $priceProductTransfers = $this->priceProductOfferReader->getProductOfferPrices($priceProductOfferCriteriaTransfer);

        if ($priceProductTransfers->count() < 1) {
            return $wishlistItemTransfer;
        }

        return $wishlistItemTransfer->setPrices($priceProductTransfers);
    }
}
