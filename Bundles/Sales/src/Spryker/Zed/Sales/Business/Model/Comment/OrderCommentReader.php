<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Comment;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderDetailsCommentsTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderCommentReader implements OrderCommentReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getCommentsByIdSalesOrder($idSalesOrder)
    {
        $commentsCollection = $this->queryContainer->queryCommentsByIdSalesOrder($idSalesOrder)->find();

        return $this->hydrateCommentCollectionFromEntityCollection($commentsCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $commentsCollection
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    protected function hydrateCommentCollectionFromEntityCollection(ObjectCollection $commentsCollection)
    {
        $comments = new OrderDetailsCommentsTransfer();
        foreach ($commentsCollection as $salesOrderCommentEntity) {
            $commentTransfer = new CommentTransfer();
            $commentTransfer = $commentTransfer->fromArray($salesOrderCommentEntity->toArray(), true);
            $comments->addComment($commentTransfer);
        }

        return $comments;
    }
}
