<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ItemExpanderPluginInterface
{
    /**
     * Specification:
     * - This plugin is executed before cart add/remove items to persistence,
     *   normally you would want to add more data (expand current cart) with details from Zed persistence (price, product details, options)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer);
}
