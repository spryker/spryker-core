<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiFactory getFactory()
 */
class MerchantProductOfferCartItemExpanderPlugin extends AbstractPlugin implements CartItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `CartItemRequestTransfer` with product offer reference and merchant reference.
     * - Finds product offer in storage by `productOfferReference`.
     * - Sets `CartItemRequestTransfer.productOfferReference` and `CartItemRequestTransfer.merchantReference` from `ProductOfferStorageTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function expand(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        return $this->getFactory()
            ->createCartItemExpander()
            ->expand($cartItemRequestTransfer, $restCartItemsAttributesTransfer);
    }
}
