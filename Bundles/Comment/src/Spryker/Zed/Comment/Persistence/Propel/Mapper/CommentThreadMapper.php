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
use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentTag;
use Orm\Zed\Comment\Persistence\SpyCommentThread;

class CommentThreadMapper
{
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
            $commentTransfers[] = $this->mapCommentEntityToCommentTransfer($commentEntity);
        }

        return new ArrayObject($commentTransfers);
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function mapCommentEntityToCommentTransfer(SpyComment $commentEntity): CommentTransfer
    {
        $commentTransfer = (new CommentTransfer())
            ->fromArray($commentEntity->toArray(), true);

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
