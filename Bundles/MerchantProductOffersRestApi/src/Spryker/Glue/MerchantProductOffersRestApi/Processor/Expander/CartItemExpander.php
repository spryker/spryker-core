<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\MerchantProductOfferStorageReaderInterface;

class CartItemExpander implements CartItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\MerchantProductOfferStorageReaderInterface
     */
    protected $merchantProductOfferStorageReader;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\MerchantProductOfferStorageReaderInterface $merchantProductOfferStorageReader
     */
    public function __construct(MerchantProductOfferStorageReaderInterface $merchantProductOfferStorageReader)
    {
        $this->merchantProductOfferStorageReader = $merchantProductOfferStorageReader;
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

        $productOfferStorageTransfer = $this->merchantProductOfferStorageReader->findProductOfferStorageByReference(
            $restCartItemsAttributesTransfer->getProductOfferReference()
        );

        return $cartItemRequestTransfer->setProductOfferReference($productOfferStorageTransfer->getProductOfferReference())
            ->setMerchantReference($productOfferStorageTransfer->getMerchantReference());
    }
}
