<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Comment;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderCommentSaver implements OrderCommentSaverInterface
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
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function save(CommentTransfer $commentTransfer)
    {
        $commentEntity = new SpySalesOrderComment();
        $commentEntity->fromArray($commentTransfer->toArray());
        $commentEntity->save();

        $this->hydrateCommentTranferFromEntity($commentTransfer, $commentEntity);

        return $commentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderComment $commentEntity
     *
     * @return void
     */
    protected function hydrateCommentTranferFromEntity(
        CommentTransfer $commentTransfer,
        SpySalesOrderComment $commentEntity
    ) {
        $commentTransfer->fromArray($commentEntity->toArray(), true);
    }
}
