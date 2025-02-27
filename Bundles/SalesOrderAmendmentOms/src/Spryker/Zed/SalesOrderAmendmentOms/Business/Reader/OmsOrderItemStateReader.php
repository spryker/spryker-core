<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Reader;

use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig;

class OmsOrderItemStateReader implements OmsOrderItemStateReaderInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig
     */
    public function __construct(
        protected SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade,
        protected SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer
     */
    public function getInitialItemState(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): OmsOrderItemStateTransfer {
        return $this->omsFacade->getOmsOrderItemState(
            $this->salesOrderAmendmentOmsConfig->getOrderAmendmentOrderItemInitialState(),
        );
    }
}
