<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderDetailsCommentsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CommentManager
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->queryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderComment
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        $commentEntity = new SpySalesOrderComment();
        $commentEntity->fromArray($commentTransfer->toArray());

        $commentEntity->save();

        $commentTransfer->fromArray($commentEntity->toArray(), true);

        return $commentTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getCommentsByIdSalesOrder($idSalesOrder)
    {
        $commentsCollection = $this->queryContainer->queryComments()->filterByFkSalesOrder($idSalesOrder)->find();

        $comments = new OrderDetailsCommentsTransfer();
        foreach ($commentsCollection as $spySalesOrderComment) {
            $comment = (new CommentTransfer())->fromArray($spySalesOrderComment->toArray(), true);
            $comments->addComment($comment);
        }

        return $comments;
    }

}
