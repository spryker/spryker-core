<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Use this plugin interface to find an order for cart reorder when customer related order is not found.
 */
interface CartReorderOrderProviderPluginInterface
{
    /**
     * Specification:
     * - Provides an order for reorder by `CartReorderRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrder(CartReorderRequestTransfer $cartReorderRequestTransfer): ?OrderTransfer;
}
