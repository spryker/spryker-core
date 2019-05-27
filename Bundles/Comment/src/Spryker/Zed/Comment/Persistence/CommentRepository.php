<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
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
        $commentThreadEntity = $this->getFactory()
            ->getCommentThreadPropelQuery()
            ->joinWithSpyComment()
            ->useSpyCommentQuery()
                ->joinWithSpyCommentCommentTag()
                ->joinWithSpyCustomer()
            ->endUse()
            ->findOne();

        if (!$commentThreadEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCommentThreadMapping()
            ->mapCommentThreadEntityToCommentThreadTransfer($commentThreadEntity, new CommentThreadTransfer());
    }
}
