<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderComment;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainer;

class CommentManager
{

    /**
     * @var SalesQueryContainer
     */
    protected $queryContainer;

    /**
     * @param SalesQueryContainer $salesQueryContainer
     */
    public function __construct(SalesQueryContainer $salesQueryContainer)
    {
        $this->queryContainer = $salesQueryContainer;
    }

    /**
     * @param CommentTransfer $commentTransfer
     *
     * @return SpySalesOrderComment
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        $comment = new SpySalesOrderComment();
        $comment->fromArray($commentTransfer->toArray());

        $comment->save();

        return $comment;
    }

}
