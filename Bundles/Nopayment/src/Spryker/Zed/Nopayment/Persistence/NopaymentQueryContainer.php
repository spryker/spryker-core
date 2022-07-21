<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Nopayment\Persistence\NopaymentPersistenceFactory getFactory()
 */
class NopaymentQueryContainer extends AbstractQueryContainer implements NopaymentQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Nopayment\Persistence\SpyNopaymentPaid>
     */
    public function queryOrderItem(SpySalesOrderItem $orderItem)
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $orderOrderItemCollection */
        $orderOrderItemCollection = $this->getFactory()->createNopaymentPaidQuery()
            ->findByFkSalesOrderItem($orderItem->getIdSalesOrderItem());

        return $orderOrderItemCollection;
    }
}
