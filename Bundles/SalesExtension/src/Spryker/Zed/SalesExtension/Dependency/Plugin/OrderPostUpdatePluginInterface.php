<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * Provides an ability to execute additional actions after an order has been updated.
 */
interface OrderPostUpdatePluginInterface
{
    /**
     * Specification:
     * - Executes additional actions after an order has been updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function execute(OrderTransfer $orderTransfer): void;
}
