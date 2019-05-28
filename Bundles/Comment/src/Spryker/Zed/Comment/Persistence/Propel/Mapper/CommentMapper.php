<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CommentTagTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Comment\Persistence\Base\SpyCommentThread;
use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentTag;

class CommentMapper
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     * @param \Orm\Zed\Comment\Persistence\SpyCommentThread $commentThreadEntity
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentThread
     */
    public function mapCommentThreadTransferToCommentThreadEntity(
        CommentThreadTransfer $commentThreadTransfer,
        SpyCommentThread $commentThreadEntity
    ): SpyCommentThread {
        $commentThreadEntity->fromArray($commentThreadTransfer->modifiedToArray());

        return $commentThreadEntity;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyCommentThread $commentThreadEntity
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function mapCommentThreadEntityToCommentThreadTransfer(
        SpyCommentThread $commentThreadEntity,
        CommentThreadTransfer $commentThreadTransfer
    ): CommentThreadTransfer {
        $commentThreadTransfer = $commentThreadTransfer->fromArray($commentThreadEntity->toArray(), true);
        $commentThreadTransfer->setComments($this->mapCommentThreadEntityToCommentTransfers($commentThreadEntity));

        return $commentThreadTransfer;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyCommentThread $commentEntity
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]|\ArrayObject
     */
    protected function mapCommentThreadEntityToCommentTransfers(SpyCommentThread $commentEntity): ArrayObject
    {
        $commentTransfers = [];

        foreach ($commentEntity->getSpyComments() as $commentEntity) {
            $commentTransfers[] = $this->mapCommentEntityToCommentTransfer($commentEntity, new CommentTransfer());
        }

        return new ArrayObject($commentTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return \Orm\Zed\Comment\Persistence\SpyComment
     */
    public function mapCommentTransferToCommentEntity(
        CommentTransfer $commentTransfer,
        SpyComment $commentEntity
    ): SpyComment {
        $commentEntity->fromArray($commentTransfer->modifiedToArray());
        $commentEntity->setFkCustomer($commentTransfer->getCustomer()->getIdCustomer());

        return $commentEntity;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function mapCommentEntityToCommentTransfer(
        SpyComment $commentEntity,
        CommentTransfer $commentTransfer
    ): CommentTransfer {
        $commentTransfer = $commentTransfer->fromArray($commentEntity->toArray(), true);

        $commentTransfer
            ->setCustomer($this->mapCommentEntityToCustomerTransfer($commentEntity))
            ->setTags($this->mapCommentEntityToCommentTagTransfers($commentEntity));

        return $commentTransfer;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function mapCommentEntityToCustomerTransfer(SpyComment $commentEntity): CustomerTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->fromArray($commentEntity->getSpyCustomer()->toArray(), true);

        return $customerTransfer;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer[]|\ArrayObject
     */
    protected function mapCommentEntityToCommentTagTransfers(SpyComment $commentEntity): ArrayObject
    {
        $commentTagTransfers = [];

        foreach ($commentEntity->getSpyCommentCommentTags() as $commentCommentTagEntity) {
            $commentTagTransfers[] = $this->mapCommentEntityToCommentTagTransfer($commentCommentTagEntity->getSpyCommentTag());
        }

        return new ArrayObject($commentTagTransfers);
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyCommentTag $commentTagEntity
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer
     */
    protected function mapCommentEntityToCommentTagTransfer(SpyCommentTag $commentTagEntity): CommentTagTransfer
    {
        $commentTagTransfer = (new CommentTagTransfer())
            ->fromArray($commentTagEntity->toArray(), true);

        return $commentTagTransfer;
    }
}
