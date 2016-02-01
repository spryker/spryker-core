<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Spryker\Zed\Sales\Persistence\SalesQueryContainer;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CommentManager
{

    /**
     * @var SalesQueryContainer
     */
    protected $queryContainer;

    /**
     * @param SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->queryContainer = $salesQueryContainer;
    }

    /**
     * @param CommentTransfer $commentTransfer
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

}
