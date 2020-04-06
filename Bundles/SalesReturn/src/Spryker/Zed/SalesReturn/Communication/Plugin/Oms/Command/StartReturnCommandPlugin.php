<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;

/**
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 */
class StartReturnCommandPlugin extends AbstractPlugin implements CommandByItemInterface
{
    /**
     * {@inheritDoc}
     * - Sets remuneration amount for Item.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data): array
    {
        $itemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($orderItem->getIdSalesOrderItem());

        $this->getFacade()->setOrderItemRemunerationAmount($itemTransfer);

        return [];
    }
}
