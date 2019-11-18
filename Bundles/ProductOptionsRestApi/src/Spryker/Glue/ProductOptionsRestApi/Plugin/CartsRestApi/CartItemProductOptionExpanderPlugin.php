<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemExpanderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class CartItemProductOptionExpanderPlugin extends AbstractPlugin implements CartItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands cart item request transfer with product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expand(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestRequestInterface $restRequest
    ): CartItemRequestTransfer {
        // TODO: Implement expand() method.
    }
}
