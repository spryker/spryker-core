<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxAppExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * Implement this plugin if you want to expand `OrderTransfer` with additional data.
 */
interface OrderTaxAppExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands order transfer and its contents with Tax App necessary data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expand(OrderTransfer $orderTransfer): OrderTransfer;
}
