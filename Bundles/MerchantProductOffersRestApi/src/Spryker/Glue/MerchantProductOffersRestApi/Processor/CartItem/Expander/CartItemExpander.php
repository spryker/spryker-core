<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface;

class CartItemExpander implements CartItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct(MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient)
    {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
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
        if (!$restCartItemsAttributesTransfer->getProductOfferReference()) {
            return $cartItemRequestTransfer;
        }

        $productOfferStorageTransfer = $this->merchantProductOfferStorageClient->findProductOfferStorageByReference(
            $restCartItemsAttributesTransfer->getProductOfferReference()
        );

        if (!$productOfferStorageTransfer) {
            return $cartItemRequestTransfer;
        }

        return $cartItemRequestTransfer
            ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReference())
            ->setMerchantReference($productOfferStorageTransfer->getMerchantReference());
    }
}
