<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentPersistenceFactory getFactory()
 */
class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        $commentThreadQuery = $this->getFactory()
            ->getCommentThreadPropelQuery()
            ->filterByOwnerId($commentRequestTransfer->getOwnerId())
            ->filterByOwnerType($commentRequestTransfer->getOwnerType())
            ->joinWithSpyComment()
            ->useSpyCommentQuery()
                ->joinWithSpyCustomer()
                ->leftJoinWithSpyCommentCommentTag()
            ->endUse();

        $commentThreadQuery = $this->setCommentThreadFilters($commentThreadQuery, $commentRequestTransfer);
        $commentThreadEntity = $commentThreadQuery->find()->getFirst();

        if (!$commentThreadEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCommentThreadMapper()
            ->mapCommentThreadEntityToCommentThreadTransfer($commentThreadEntity, new CommentThreadTransfer());
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyCommentThreadQuery $commentThreadQuery
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentThreadQuery
     */
    protected function setCommentThreadFilters(
        SpyCommentThreadQuery $commentThreadQuery,
        CommentRequestTransfer $commentRequestTransfer
    ): SpyCommentThreadQuery {
        if ($commentRequestTransfer->getComment() && $commentRequestTransfer->getComment()->getUuid()) {
            $commentThreadQuery
                ->useSpyCommentQuery()
                ->filterByUuid($commentRequestTransfer->getComment()->getUuid())
                ->endUse();
        }

        return $commentThreadQuery;
    }
}
