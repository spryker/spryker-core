<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Business;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method \Spryker\Zed\CommentSalesConnector\Business\CommentSalesConnectorBusinessFactory getFactory()
 */
interface CommentSalesConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expects commentThread.ownerId in QuoteTransfer to be provided.
     * - Expects commentThread.ownerType in QuoteTransfer to be provided.
     * - Expects idSalesOrder to be provided.
     * - Removes found comment thread when `$forceDelete` is true.
     * - Duplicates comment thread from quote to order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool|null $forceDelete
     *
     * @return void
     */
    public function attachCommentThreadToOrder(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer,
        ?bool $forceDelete = false
    ): void;

    /**
     * Specification:
     * - Expects idSalesOrder to be provided.
     * - Retrieves CommentThread by order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOrder(OrderTransfer $orderTransfer): ?CommentThreadTransfer;
}
