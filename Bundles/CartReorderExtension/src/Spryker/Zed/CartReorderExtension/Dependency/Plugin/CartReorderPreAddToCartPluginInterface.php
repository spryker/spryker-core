<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;

/**
 * Use this plugin interface to execute actions before adding reorder items to cart.
 */
interface CartReorderPreAddToCartPluginInterface
{
    /**
     * Specification:
     * - Plugin is executed before adding reorder items to cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function preAddToCart(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
