<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Business;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;

interface CommentUserConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expands `CommentTransfer` with `UserTransfer` if `CommentTransfer.fkUser` is set.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function expandCommentsWithUser(array $commentTransfers): array;

    /**
     * Specification:
     * - Requires `CommentRequestTransfer.comment` and `CommentRequestTransfer.comment.fkUser` transfer properties to be set.
     * - Validates that the comment does not have two authors customer and user.
     * - Validates if user with provided ID exists.
     * - If `CommentRequestTransfer.comment.idComment` is set, validates if this comments belongs to user.
     * - Returns `CommentValidationResponseTransfer` with validation error messages if validation failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentAuthor(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer;
}
