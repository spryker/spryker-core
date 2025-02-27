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
use Orm\Zed\Comment\Persistence\SpyCommentTag;
use Orm\Zed\Comment\Persistence\SpyCommentThread;
use Orm\Zed\Comment\Persistence\SpyCommentToCommentTag;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentPersistenceFactory getFactory()
 */
class CommentEntityManager extends AbstractEntityManager implements CommentEntityManagerInterface
{
    /**
     * @var string
     */
    protected const COLUMN_IS_DELETED = 'IsDeleted';

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d H:i:s.u';

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

        $commentThreadTransfer
            ->setIdCommentThread($commentThreadEntity->getIdCommentThread())
            ->setUuid($commentThreadEntity->getUuid());

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
            ->setUuid($commentEntity->getUuid())
            ->setCreatedAt($commentEntity->getCreatedAt(static::DATE_FORMAT))
            ->setUpdatedAt($commentEntity->getUpdatedAt(static::DATE_FORMAT));

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

        $commentMapper = $this->getFactory()->createCommentMapper();
        $commentEntity = $commentMapper->mapCommentTransferToCommentEntity($commentTransfer, $commentEntity);

        $commentEntity->save();

        return $commentMapper->mapCommentEntityToCommentTransfer($commentEntity, $commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function removeComment(CommentTransfer $commentTransfer): void
    {
        $commentTransfer->requireUuid();

        $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByUuid($commentTransfer->getUuid())
            ->update([static::COLUMN_IS_DELETED => true]);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function addCommentTagsToComment(CommentTransfer $commentTransfer): void
    {
        $commentTransfer->requireIdComment();

        foreach ($commentTransfer->getCommentTags() as $commentTagTransfer) {
            $commentCommentTagEntity = $this->getFactory()
                ->getCommentToCommentTagPropelQuery()
                ->filterByFkComment($commentTransfer->getIdComment())
                ->filterByFkCommentTag($commentTagTransfer->getIdCommentTag())
                ->findOne();

            if ($commentCommentTagEntity) {
                continue;
            }

            $commentCommentTagEntity = (new SpyCommentToCommentTag())
                ->setFkCommentTag($commentTagTransfer->getIdCommentTag())
                ->setFkComment($commentTransfer->getIdComment());

            $commentCommentTagEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function removeCommentTagsFromComment(CommentTransfer $commentTransfer): void
    {
        $commentTransfer->requireIdComment();

        $assignedCommentTagIds = [];

        foreach ($commentTransfer->getCommentTags() as $commentTagTransfer) {
            $assignedCommentTagIds[] = $commentTagTransfer->getIdCommentTag();
        }

        $this->getFactory()
            ->getCommentToCommentTagPropelQuery()
            ->filterByFkComment($commentTransfer->getIdComment())
            ->filterByFkCommentTag($assignedCommentTagIds, Criteria::NOT_IN)
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
     * @param int $idCommentThread
     *
     * @return void
     */
    public function removeCommentThread(int $idCommentThread): void
    {
        $commentToCommentTagEntities = $this->getFactory()
            ->getCommentToCommentTagPropelQuery()
            ->joinWithSpyComment()
            ->useSpyCommentQuery()
            ->joinWithSpyCommentThread()
                ->filterByFkCommentThread($idCommentThread)
            ->endUse()
            ->find();

        foreach ($commentToCommentTagEntities as $entity) {
            $entity->delete();
        }

        $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByFkCommentThread($idCommentThread)
            ->delete();

        $this->getFactory()
            ->getCommentThreadPropelQuery()
            ->filterByIdCommentThread($idCommentThread)
            ->delete();
    }
}
