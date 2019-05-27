<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentCollectionTransfer;
use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionCollectionTransfer;
use Generated\Shared\Transfer\CommentVersionFilterTransfer;

interface CommentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentCollectionTransfer
     */
    public function getCommentCollectionByFilter(
        CommentFilterTransfer $quoteRequestFilterTransfer
    ): CommentCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionCollectionTransfer
     */
    public function getCommentVersionCollectionByFilter(
        CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer
    ): CommentVersionCollectionTransfer;

    /**
     * @param string $customerReference
     *
     * @return int
     */
    public function countCustomerComments(string $customerReference): int;

    /**
     * @param string $versionReference
     *
     * @return \Generated\Shared\Transfer\CommentTransfer|null
     */
    public function findCommentByVersionReference(string $versionReference): ?CommentTransfer;
}
