<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

interface OrderItemsTableExpanderPluginInterface
{
    /**
     * Specification:
     * - Returns column name.
     *
     * @api
     *
     * @return string
     */
    public function getColumnName(): string;

    /**
     * Specification:
     * - Returns row column value.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function getColumnCellContent(ItemTransfer $itemTransfer): string;
}
