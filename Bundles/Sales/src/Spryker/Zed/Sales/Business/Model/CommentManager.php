<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderDetailsCommentsTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CommentManager
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainer|SalesQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     */
    public function __construct(\Spryker\Zed\Sales\Persistence\SalesQueryContainer $queryContainer, UserTransfer $userTransfer)
    {
        $this->queryContainer = $queryContainer;
        $this->userTransfer = $userTransfer;
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
        $commentEntity->setUsername(sprintf(
            '%s %s',
            $this->userTransfer->getFirstName(),
            $this->userTransfer->getLastName())
        );

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
