<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderComment;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainer;

class CommentManager
{
    protected $queryContainer;

    public function __construct(SalesQueryContainer $salesQueryContainer)
    {
        $this->queryContainer = $salesQueryContainer;
    }

    public function saveComment(CommentTransfer $commentTransfer)
    {
        $comment = new SpySalesOrderComment();
        $comment->fromArray($commentTransfer->toArray());

        $comment->save();

        return $comment;
    }
}
