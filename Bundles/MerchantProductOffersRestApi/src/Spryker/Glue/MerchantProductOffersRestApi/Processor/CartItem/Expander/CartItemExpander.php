<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToProductOfferStorageClientInterface;

class CartItemExpander implements CartItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct(MerchantProductOffersRestApiToProductOfferStorageClientInterface $productOfferStorageClient)
    {
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function expand(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        $cartItemRequestTransfer
            ->setProductOfferReference($restCartItemsAttributesTransfer->getProductOfferReference())
            ->setMerchantReference($restCartItemsAttributesTransfer->getMerchantReference());

        $productOfferStorageTransfer = null;

        /** @var string $productOfferReference */
        $productOfferReference = $restCartItemsAttributesTransfer->getProductOfferReference();
        if ($productOfferReference) {
            $productOfferStorageTransfer = $this->productOfferStorageClient
                ->findProductOfferStorageByReference($productOfferReference);
        }

        if ($productOfferStorageTransfer !== null) {
            $cartItemRequestTransfer
                ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReference())
                ->setMerchantReference($productOfferStorageTransfer->getMerchantReference());
        }

        return $cartItemRequestTransfer;
    }
}
