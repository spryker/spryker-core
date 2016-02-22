<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Command;

use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Library\Log;

class DecreaseStock implements CommandByItemInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        $message = sprintf('Command DecreaseStock by Item for Item %s (quantity %s)', $orderItem->getIdSalesOrderItem(), $orderItem->getQuantity());
        Log::log($message, 'statemachine.log');

        return [];
    }

}
