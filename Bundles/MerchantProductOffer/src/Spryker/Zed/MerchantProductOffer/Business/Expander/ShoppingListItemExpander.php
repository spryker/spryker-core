<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Expander;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemCollectionWithMerchantReference(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $productOfferCriteriaTransfer = $this->getProductOfferCriteriaTransfer($shoppingListItemCollectionTransfer);

        if (count($productOfferCriteriaTransfer->getProductOfferReferences()) === 0) {
            return $shoppingListItemCollectionTransfer;
        }

        $productOfferCollectionTransfer = $this->productOfferFacade->get($productOfferCriteriaTransfer);

        $merchantReferencesIndexedByProductOfferReference = $this->getMerchantReferencesIndexedByProductOfferReference(
            $productOfferCollectionTransfer,
        );

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $productOfferReference = $shoppingListItemTransfer->getProductOfferReference();
            $merchantReference = $merchantReferencesIndexedByProductOfferReference[$productOfferReference] ?? null;

            if ($merchantReference) {
                $shoppingListItemTransfer->setMerchantReference($merchantReference);
            }
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    protected function getProductOfferCriteriaTransfer(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ProductOfferCriteriaTransfer {
        $productOfferCriteriaTransfer = new ProductOfferCriteriaTransfer();

        foreach ($shoppingListItemCollectionTransfer->getItems() as $item) {
            if ($item->getProductOfferReference()) {
                $productOfferCriteriaTransfer->addProductOfferReference($item->getProductOfferReferenceOrFail());
            }
        }

        return $productOfferCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array
     */
    protected function getMerchantReferencesIndexedByProductOfferReference(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): array {
        $merchantReferencesIndexedByProductOfferReference = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOffer) {
            $merchantReferencesIndexedByProductOfferReference[$productOffer->getProductOfferReference()] = $productOffer->getMerchantReference();
        }

        return $merchantReferencesIndexedByProductOfferReference;
    }
}
