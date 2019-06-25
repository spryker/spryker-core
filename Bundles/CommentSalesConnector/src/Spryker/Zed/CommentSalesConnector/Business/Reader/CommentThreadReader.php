<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Business\Reader;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CommentSalesConnector\CommentSalesConnectorConfig;
use Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface;

class CommentThreadReader implements CommentThreadReaderInterface
{
    /**
     * @var \Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface
     */
    protected $commentFacade;

    /**
     * @param \Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface $commentFacade
     */
    public function __construct(CommentSalesConnectorToCommentFacadeInterface $commentFacade)
    {
        $this->commentFacade = $commentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOrder(OrderTransfer $orderTransfer): ?CommentThreadTransfer
    {
        $orderTransfer->requireIdSalesOrder();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($orderTransfer->getIdSalesOrder())
            ->setOwnerType(CommentSalesConnectorConfig::COMMENT_THREAD_SALES_ORDER_OWNER_TYPE);

        return $this->commentFacade->findCommentThreadByOwner($commentRequestTransfer);
    }
}
