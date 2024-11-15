<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorderExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Use this plugin interface to filter reorder items.
 */
interface CartReorderItemFilterPluginInterface
{
    /**
     * Specification:
     * - Filters reorder items.
     * - Uses the provided `CartReorderRequestTransfer` parameters to filter reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function filter(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        OrderTransfer $orderTransfer
    ): ArrayObject;
}
