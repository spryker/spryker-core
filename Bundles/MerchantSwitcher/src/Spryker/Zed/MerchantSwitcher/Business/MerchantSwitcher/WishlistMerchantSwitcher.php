<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface;

class WishlistMerchantSwitcher implements WishlistMerchantSwitcherInterface
{
    /**
     * @var \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface
     */
    protected $merchantProductOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface $merchantProductOfferFacade
     */
    public function __construct(MerchantSwitcherToMerchantProductOfferFacadeInterface $merchantProductOfferFacade)
    {
        $this->merchantProductOfferFacade = $merchantProductOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInWishlistItems(
        MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
    ): MerchantSwitchResponseTransfer {
        $merchantSwitchRequestTransfer
            ->requireMerchantReference()
            ->requireWishlist();

        $wishlistTransfer = $merchantSwitchRequestTransfer->getWishlist();
        $merchantReference = $merchantSwitchRequestTransfer->getMerchantReference();

        $productOfferTransfers = $this
            ->getProductOffersForWishlistItems($merchantReference, $wishlistTransfer)
            ->getProductOffers();

        foreach ($wishlistTransfer->getWishlistItems() as $wishlistItemTransfer) {
            $productOfferTransfer = $this->findProductOfferBySku(
                $productOfferTransfers,
                $wishlistItemTransfer->getSku()
            );

            if ($productOfferTransfer) {
                $wishlistItemTransfer
                    ->setMerchantReference($merchantReference)
                    ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
            }
        }

        return (new MerchantSwitchResponseTransfer())
            ->setWishlist($wishlistTransfer);
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOfferTransfer[] $productOfferTransfers
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    protected function findProductOfferBySku(ArrayObject $productOfferTransfers, string $sku): ?ProductOfferTransfer
    {
        foreach ($productOfferTransfers as $productOfferTransfer) {
            if ($productOfferTransfer->getConcreteSku() === $sku) {
                return $productOfferTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    protected function getProductOffersForWishlistItems(
        string $merchantReference,
        WishlistTransfer $wishlistTransfer
    ): ProductOfferCollectionTransfer {
        $merchantProductOfferCriteriaTransfer = (new MerchantProductOfferCriteriaTransfer())
            ->setMerchantReference($merchantReference)
            ->setIsActive(true);

        foreach ($wishlistTransfer->getWishlistItems() as $item) {
            $merchantProductOfferCriteriaTransfer->addSku($item->getSku());
        }

        return $this->merchantProductOfferFacade->getProductOfferCollection($merchantProductOfferCriteriaTransfer);
    }
}
