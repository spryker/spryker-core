<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferencePersistenceFactory getFactory()
 */
class OrderCustomReferenceEntityManager extends AbstractEntityManager implements OrderCustomReferenceEntityManagerInterface
{
    protected const COLUMN_ORDER_CUSTOM_REFERENCE = 'OrderCustomReference';
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomReference(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderQuery = $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->filterByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        if (!$salesOrderQuery->findOne()) {
            return;
        }

        $salesOrderQuery->update([static::COLUMN_ORDER_CUSTOM_REFERENCE => $quoteTransfer->getOrderCustomReference()]);
    }
}
