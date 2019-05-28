<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentThread;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentPersistenceFactory getFactory()
 */
class CommentEntityManager extends AbstractEntityManager implements CommentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function createCommentThread(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer
    {
        $commentThreadEntity = $this->getFactory()
            ->createCommentThreadMapper()
            ->mapCommentThreadTransferToCommentThreadEntity($commentThreadTransfer, new SpyCommentThread());

        $commentThreadEntity->save();
        $commentThreadTransfer->setIdCommentThread($commentThreadEntity->getIdCommentThread());

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function createComment(CommentTransfer $commentTransfer): CommentTransfer
    {
        $commentEntity = $this->getFactory()
            ->createCommentMapper()
            ->mapCommentTransferToCommentEntity($commentTransfer, new SpyComment());

        $commentEntity->save();
        $commentTransfer->setIdComment($commentEntity->getIdComment());

        return $commentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function updateComment(CommentTransfer $commentTransfer): CommentTransfer
    {
        $commentEntity = $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByUuid($commentTransfer->getUuid())
            ->findOne();

        $commentEntity = $this->getFactory()
            ->createCommentMapper()
            ->mapCommentTransferToCommentEntity($commentTransfer, $commentEntity);

        $commentEntity->save();

        return $commentTransfer;
    }
}
