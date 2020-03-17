<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderListTransfer;

interface OrderSearchQueryExpanderPluginInterface
{
    /**
     * Specification:
     * - Returns true if plugin is applicable for given filters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return bool
     */
    public function isApplicable(OrderListTransfer $orderListTransfer): bool;

    /**
     * Specification:
     * - Expands OrderListTransfer:queryJoins with additional QueryJoinTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function expand(OrderListTransfer $orderListTransfer): OrderListTransfer;
}
