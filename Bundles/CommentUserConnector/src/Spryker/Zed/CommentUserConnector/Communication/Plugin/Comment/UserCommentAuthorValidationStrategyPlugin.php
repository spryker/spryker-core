<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Spryker\Zed\CommentExtension\Dependency\Plugin\CommentAuthorValidatorStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CommentUserConnector\CommentUserConnectorConfig getConfig()
 * @method \Spryker\Zed\CommentUserConnector\Business\CommentUserConnectorFacadeInterface getFacade()
 */
class UserCommentAuthorValidationStrategyPlugin extends AbstractPlugin implements CommentAuthorValidatorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `CommentTransfer.fkUser` is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function isApplicable(CommentRequestTransfer $commentRequestTransfer): bool
    {
        return $commentRequestTransfer->getCommentOrFail()->getFkUser() !== null;
    }

    /**
     * {@inheritDoc}
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
    public function validate(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer
    {
        return $this->getFacade()->validateCommentAuthor($commentRequestTransfer);
    }
}
