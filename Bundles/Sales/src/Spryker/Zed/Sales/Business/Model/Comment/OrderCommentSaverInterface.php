<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Sales\Business\Model\Comment;

use Generated\Shared\Transfer\CommentTransfer;

interface OrderCommentSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderComment
     */
    public function save(CommentTransfer $commentTransfer);
}
