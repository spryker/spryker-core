<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderTotalsAggregatePluginInterface
{

    /**
     * Aggregates data and adds it to the transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer);

}
