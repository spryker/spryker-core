<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface CustomerOrderPreCheckPluginInterface
{
    /**
     * Specification:
     * - Executes plugins before a customer order is retrieved.
     * - Checks if customer order is applicable for retrieval.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function check(OrderTransfer $orderTransfer, CustomerTransfer $customerTransfer): bool;
}
