<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;

/**
 * @method \Spryker\Zed\CommentSalesConnector\CommentSalesConnectorConfig getConfig()
 * @method \Spryker\Zed\CommentSalesConnector\Business\CommentSalesConnectorFacadeInterface getFacade()
 */
class SaveOrderCommentThreadOrderPostSavePlugin extends AbstractPlugin implements OrderPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.commentThread` or `QuoteTransfer.commentThread.comments` is empty.
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Removes found comment thread.
     * - Duplicates comment thread from quote to order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        if ($quoteTransfer->getCommentThread() && $quoteTransfer->getCommentThread()->getComments()->count()) {
            $this->getFacade()->attachCommentThreadToOrder($saveOrderTransfer, $quoteTransfer, true);
        }

        return $saveOrderTransfer;
    }
}
