<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorderExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;

/**
 * Use this plugin interface to filter reorder items.
 */
interface CartReorderOrderItemFilterPluginInterface
{
    /**
     * Specification:
     * - Filters reorder items.
     * - Uses the provided `CartReorderRequestTransfer` parameters to filter reorder items.
     * - Returns filtered array of `ItemTransfer` objects.
     *
     * @api
     *
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $filteredItems
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function filter(ArrayObject $filteredItems, CartReorderRequestTransfer $cartReorderRequestTransfer): ArrayObject;
}
