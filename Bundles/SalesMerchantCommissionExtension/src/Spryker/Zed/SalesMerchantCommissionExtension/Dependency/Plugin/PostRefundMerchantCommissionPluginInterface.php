<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * Use this plugin to extend business logic of merchant commission refund.
 */
interface PostRefundMerchantCommissionPluginInterface
{
    /**
     * Specification:
     * - Executes after merchant commission amounts were refunded.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function execute(OrderTransfer $orderTransfer, array $itemTransfers): void;
}
