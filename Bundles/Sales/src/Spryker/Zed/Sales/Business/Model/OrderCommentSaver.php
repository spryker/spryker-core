<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderCommentSaver implements OrderCommentSaverInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainer
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
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderComment
     */
    public function save(CommentTransfer $commentTransfer)
    {
        $commentEntity = new SpySalesOrderComment();
        $commentEntity->fromArray($commentTransfer->toArray());
        $commentEntity->save();

        $commentTransfer->fromArray($commentEntity->toArray(), true);

        return $commentTransfer;
    }
}
