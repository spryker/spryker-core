<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

interface SalesOrderItemTransformerPluginInterface
{
    /**
     * Specification:
     *  - Returns true if plugin is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ItemTransfer $itemTransfer): bool;

    /**
     * Specification:
     *  - Gets item from order and expands it if needed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformOrderItem(ItemTransfer $itemTransfer): ArrayObject;
}
