<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CommentSalesConnector\Business\CommentSalesConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CommentSalesConnector\CommentSalesConnectorConfig getConfig()
 */
class CommentThreadOrderExpanderPlugin extends AbstractPlugin implements OrderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes when idSalesOrder provided.
     * - Expands OrderTransfer with CommentThread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        if ($orderTransfer->getIdSalesOrder()) {
            $orderTransfer->setCommentThread($this->getFacade()->findCommentThreadByOrder($orderTransfer));
        }

        return $orderTransfer;
    }
}
