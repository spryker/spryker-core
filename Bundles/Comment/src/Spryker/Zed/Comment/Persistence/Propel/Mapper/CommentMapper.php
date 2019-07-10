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
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\Collection\ObjectCollection;

class CommentMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Comment\Persistence\SpyCommentTag[] $commentTagEntities
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer[]
     */
    public function mapCommentTagEntitiesToCommentTagTransfers(ObjectCollection $commentTagEntities): array
    {
        $commentTagTransfers = [];

        foreach ($commentTagEntities as $commentTagEntity) {
            $commentTagTransfers[] = $this->mapCommentTagEntityToCommentTagTransfer($commentTagEntity, new CommentTagTransfer());
        }

        return $commentTagTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagTransfer $commentTagTransfer
     * @param \Orm\Zed\Comment\Persistence\SpyCommentTag $commentTagEntity
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentTag
     */
    public function mapCommentTagTransferToCommentTagEntity(
        CommentTagTransfer $commentTagTransfer,
        SpyCommentTag $commentTagEntity
    ): SpyCommentTag {
        $commentTagEntity->fromArray($commentTagTransfer->modifiedToArray());

        return $commentTagEntity;
    }

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

        return $commentThreadTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Comment\Persistence\SpyComment[] $commentEntities
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function mapCommentEntitiesToCommentTransfers(ObjectCollection $commentEntities): array
    {
        $commentTransfers = [];

        foreach ($commentEntities as $commentEntity) {
            $commentTransfers[] = $this->mapCommentEntityToCommentTransfer($commentEntity, new CommentTransfer());
        }

        return $commentTransfers;
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

        $commentEntity
            ->setFkCustomer($commentTransfer->getCustomer()->getIdCustomer())
            ->setFkCommentThread($commentTransfer->getIdCommentThread());

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
            ->setIdCommentThread($commentEntity->getFkCommentThread())
            ->setCommentTags(new ArrayObject($this->mapCommentEntityToCommentTagTransfers($commentEntity)));

        if ($commentEntity->getSpyCustomer()) {
            $commentTransfer->setCustomer($this->mapCustomerEntityToCustomerTransfer($commentEntity->getSpyCustomer(), new CustomerTransfer()));
        }

        return $commentTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function mapCustomerEntityToCustomerTransfer(SpyCustomer $customerEntity, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerTransfer = $customerTransfer->fromArray($customerEntity->toArray(), true);

        return $customerTransfer;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer[]
     */
    protected function mapCommentEntityToCommentTagTransfers(SpyComment $commentEntity): array
    {
        $commentTagTransfers = [];

        foreach ($commentEntity->getSpyCommentToCommentTags() as $commentCommentTagEntity) {
            $commentTagTransfers[] = $this->mapCommentTagEntityToCommentTagTransfer($commentCommentTagEntity->getSpyCommentTag(), new CommentTagTransfer());
        }

        return $commentTagTransfers;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyCommentTag $commentTagEntity
     * @param \Generated\Shared\Transfer\CommentTagTransfer $commentTagTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer
     */
    protected function mapCommentTagEntityToCommentTagTransfer(
        SpyCommentTag $commentTagEntity,
        CommentTagTransfer $commentTagTransfer
    ): CommentTagTransfer {
        $commentTagTransfer = $commentTagTransfer->fromArray($commentTagEntity->toArray(), true);

        return $commentTagTransfer;
    }
}
