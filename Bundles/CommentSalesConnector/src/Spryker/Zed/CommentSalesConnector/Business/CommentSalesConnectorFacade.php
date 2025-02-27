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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CommentSalesConnector\Business\CommentSalesConnectorBusinessFactory getFactory()
 */
class CommentSalesConnectorFacade extends AbstractFacade implements CommentSalesConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
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
    ): void {
        $this->getFactory()
            ->createCommentThreadWriter()
            ->attachCommentThreadToOrder($saveOrderTransfer, $quoteTransfer, $forceDelete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOrder(OrderTransfer $orderTransfer): ?CommentThreadTransfer
    {
        return $this->getFactory()
            ->createCommentThreadReader()
            ->findCommentThreadByOrder($orderTransfer);
    }
}
