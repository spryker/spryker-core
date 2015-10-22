<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderComment;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;

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
     * @return SpySalesOrderComment
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
