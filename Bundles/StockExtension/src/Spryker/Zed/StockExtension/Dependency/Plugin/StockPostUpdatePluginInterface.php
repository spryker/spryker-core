<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;

/**
 * Implement this plugin interface to add logic after stock is updated.
 */
interface StockPostUpdatePluginInterface
{
    /**
     * Specification:
     * - Executes after a stock is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function postUpdate(StockTransfer $stockTransfer): StockResponseTransfer;
}
