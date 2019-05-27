<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use DateTime;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentVersion;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Comment\CommentConfig as SharedCommentConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentPersistenceFactory getFactory()
 */
class CommentEntityManager extends AbstractEntityManager implements CommentEntityManagerInterface
{
    protected const COLUMN_STATUS = 'Status';

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function createComment(CommentTransfer $quoteRequestTransfer): CommentTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->createCommentMapper()
            ->mapCommentTransferToCommentEntity($quoteRequestTransfer, new SpyComment());

        $quoteRequestEntity->save();
        $quoteRequestTransfer->setIdComment($quoteRequestEntity->getIdComment());

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function updateComment(CommentTransfer $quoteRequestTransfer): CommentTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByIdComment($quoteRequestTransfer->getIdComment())
            ->findOne();

        $quoteRequestEntity = $this->getFactory()
            ->createCommentMapper()
            ->mapCommentTransferToCommentEntity($quoteRequestTransfer, $quoteRequestEntity);

        $quoteRequestEntity->save();

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionTransfer
     */
    public function createCommentVersion(CommentVersionTransfer $quoteRequestVersionTransfer): CommentVersionTransfer
    {
        $quoteRequestVersionEntity = $this->getFactory()
            ->createCommentVersionMapper()
            ->mapCommentVersionTransferToCommentVersionEntity($quoteRequestVersionTransfer, new SpyCommentVersion());

        $quoteRequestVersionEntity->save();
        $quoteRequestVersionTransfer->setIdCommentVersion($quoteRequestVersionEntity->getIdCommentVersion());

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionTransfer
     */
    public function updateCommentVersion(CommentVersionTransfer $quoteRequestVersionTransfer): CommentVersionTransfer
    {
        $quoteRequestVersionEntity = $this->getFactory()
            ->getCommentVersionPropelQuery()
            ->filterByIdCommentVersion($quoteRequestVersionTransfer->getIdCommentVersion())
            ->findOne();

        $quoteRequestVersionEntity = $this->getFactory()
            ->createCommentVersionMapper()
            ->mapCommentVersionTransferToCommentVersionEntity($quoteRequestVersionTransfer, $quoteRequestVersionEntity);

        $quoteRequestVersionEntity->save();

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \DateTime $validUntil
     *
     * @return void
     */
    public function closeOutdatedComments(DateTime $validUntil): void
    {
        $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByStatus(SharedCommentConfig::STATUS_READY)
            ->filterByValidUntil($validUntil, Criteria::LESS_EQUAL)
            ->update([static::COLUMN_STATUS => SharedCommentConfig::STATUS_CLOSED]);
    }

    /**
     * @param string $quoteRequestReference
     * @param string $fromStatus
     * @param string $toStatus
     *
     * @return bool
     */
    public function updateCommentStatus(string $quoteRequestReference, string $fromStatus, string $toStatus): bool
    {
        return (bool)$this->getFactory()
            ->getCommentPropelQuery()
            ->filterByCommentReference($quoteRequestReference)
            ->filterByStatus($fromStatus)
            ->update([static::COLUMN_STATUS => $toStatus]);
    }
}
