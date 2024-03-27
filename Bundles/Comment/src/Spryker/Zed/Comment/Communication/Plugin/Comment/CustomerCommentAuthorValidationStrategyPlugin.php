<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Plugin\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Spryker\Zed\CommentExtension\Dependency\Plugin\CommentAuthorValidatorStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 * @method \Spryker\Zed\Comment\Communication\CommentCommunicationFactory getFactory()
 */
class CustomerCommentAuthorValidationStrategyPlugin extends AbstractPlugin implements CommentAuthorValidatorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `CommentTransfer.customer.idCustomer` is provided.
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
        return $commentRequestTransfer->getCommentOrFail()->getCustomer()
            && $commentRequestTransfer->getCommentOrFail()->getCustomer()->getIdCustomer();
    }

    /**
     * {@inheritDoc}
     * - Requires `CommentRequestTransfer.comment` and `CommentRequestTransfer.comment.customer.idCustomer` transfer properties to be set.
     * - Validates if customer with provided ID exists.
     * - If `CommentRequestTransfer.comment.idComment` is set, validates if this comments belongs to customer.
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
