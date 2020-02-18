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
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomReference(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderQuery = $this->getFactory()->getSalesOrderPropelQuery();
        $salesOrderEntity = $salesOrderQuery->filterByIdSalesOrder($saveOrderTransfer->getIdSalesOrder())->findOne();

        if (!$salesOrderEntity) {
            return;
        }

        $salesOrderEntity->setOrderCustomReference($quoteTransfer->getOrderCustomReference());
        $salesOrderEntity->save();
    }
}
