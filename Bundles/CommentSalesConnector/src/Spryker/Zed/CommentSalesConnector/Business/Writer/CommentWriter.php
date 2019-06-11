<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Business\Writer;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CommentSalesConnector\Dependency\Facade\CommentSalesConnectorToCommentFacadeInterface;

class CommentWriter implements CommentWriterInterface
{
    /**
     * @uses \Spryker\Zed\Comment\CommentConfig::COMMENT_TAG_ATTACHED
     */
    protected const COMMENT_TAG_ATTACHED = 'attached';

    protected const COMMENT_THREAD_SALES_ORDER_OWNER_TYPE = 'sales_order';

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
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function attachCommentThreadToOrder(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer
            ->requireCommentThread()
            ->getCommentThread()
                ->requireOwnerType()
                ->requireOwnerId();

        $saveOrderTransfer->requireIdSalesOrder();

        $commentFilterTransfer = (new CommentFilterTransfer())
            ->setOwnerId($quoteTransfer->getCommentThread()->getOwnerId())
            ->setOwnerType($quoteTransfer->getCommentThread()->getOwnerType())
            ->setTags([static::COMMENT_TAG_ATTACHED]);

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($saveOrderTransfer->getIdSalesOrder())
            ->setOwnerType(static::COMMENT_THREAD_SALES_ORDER_OWNER_TYPE);

        $this->commentFacade->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }
}
