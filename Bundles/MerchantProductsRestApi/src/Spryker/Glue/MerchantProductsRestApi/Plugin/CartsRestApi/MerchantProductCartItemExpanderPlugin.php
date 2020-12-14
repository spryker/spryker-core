<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductsRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\MerchantProductsRestApi\MerchantProductsRestApiFactory getFactory()
 */
class MerchantProductCartItemExpanderPlugin extends AbstractPlugin implements CartItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands CartItemRequestTransfer with merchant reference.
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
        $cartItemRequestTransfer->setMerchantReference($restCartItemsAttributesTransfer->getMerchantReference());

        return $cartItemRequestTransfer;
    }
}
