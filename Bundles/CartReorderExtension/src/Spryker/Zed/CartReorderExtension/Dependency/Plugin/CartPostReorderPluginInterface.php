<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderTransfer;

/**
 * Use this plugin interface to execute actions after reordering an order.
 */
interface CartPostReorderPluginInterface
{
    /**
     * Specification:
     * - Plugin is triggered after reordering an order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function postReorder(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
