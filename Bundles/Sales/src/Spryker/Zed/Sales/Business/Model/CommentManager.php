<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Spryker\Zed\Sales\Persistence\SalesQueryContainer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderDetailsCommentsTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;

/**
 * TODO FW Interface missing
 */
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
     * TODO FW This class is stateful because of $userTransfer
     *
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainer|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     */
    public function __construct(SalesQueryContainer $queryContainer, UserTransfer $userTransfer)
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

        // TODO FW filter method is not allowed outside of query container
        $commentsCollection = $this->queryContainer->queryComments()->filterByFkSalesOrder($idSalesOrder)->find();

        $comments = new OrderDetailsCommentsTransfer();
        foreach ($commentsCollection as $spySalesOrderComment) {
            $comment = (new CommentTransfer())->fromArray($spySalesOrderComment->toArray(), true);
            $comments->addComment($comment);
        }

        return $comments;
    }

}
