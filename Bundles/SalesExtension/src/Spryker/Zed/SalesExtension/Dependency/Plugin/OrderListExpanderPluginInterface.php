<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderListTransfer;

/**
 * Provides expansion capabilities.
 *
 * Use this plugin interface for expanding OrderListTransfer with additional data.
 */
interface OrderListExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands OrderListTransfer after order list was read from Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function expand(OrderListTransfer $orderListTransfer): OrderListTransfer;
}
