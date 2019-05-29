<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentTagTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentCommentTag;
use Orm\Zed\Comment\Persistence\SpyCommentTag;
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
            ->createCommentMapper()
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

        $commentTransfer
            ->setIdComment($commentEntity->getIdComment())
            ->setUuid($commentEntity->getUuid());

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

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function removeComment(CommentTransfer $commentTransfer): void
    {
        $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByUuid($commentTransfer->getUuid())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagTransfer $commentTagTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer
     */
    public function createCommentTag(CommentTagTransfer $commentTagTransfer): CommentTagTransfer
    {
        $commentTagEntity = $this->getFactory()
            ->createCommentMapper()
            ->mapCommentTagTransferToCommentTagEntity($commentTagTransfer, new SpyCommentTag());

        $commentTagEntity->save();

        $commentTagTransfer
            ->setIdCommentTag($commentTagEntity->getIdCommentTag())
            ->setUuid($commentTagEntity->getUuid());

        return $commentTagTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function addCommentTagsToComment(CommentTransfer $commentTransfer): CommentTransfer
    {
        $commentCommentTagQuery = $this->getFactory()
            ->getCommentCommentTagPropelQuery();

        foreach ($commentTransfer->getTags() as $commentTagTransfer) {
            $commentTagEntity = $commentCommentTagQuery
                ->filterByFkComment($commentTransfer->getIdComment())
                ->filterByFkCommentTag($commentTagTransfer->getIdCommentTag())
                ->findOne();

            if ($commentTagEntity) {
                continue;
            }

            $commentCommentTagEntity = (new SpyCommentCommentTag())
                ->setFkCommentTag($commentTagTransfer->getIdCommentTag())
                ->setFkComment($commentTransfer->getIdComment());

            $commentCommentTagEntity->save();
        }

        return $commentTransfer;
    }
}
