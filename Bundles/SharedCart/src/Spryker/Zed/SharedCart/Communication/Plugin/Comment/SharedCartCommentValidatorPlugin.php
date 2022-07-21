<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class SharedCartCommentValidatorPlugin extends AbstractPlugin implements CommentValidatorPluginInterface
{
    /**
     * @var string
     */
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'sales_order';

    /**
     * {@inheritDoc}
     * - Checks if `CommentRequestTransfer.ownerType` is "sales_order".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CommentRequestTransfer $commentRequestTransfer): bool
    {
        return $commentRequestTransfer->getOwnerTypeOrFail() === static::COMMENT_THREAD_QUOTE_OWNER_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Requires 'CommentRequestTransfer.ownerId', `CommentRequestTransfer.comment`, `CommentRequestTransfer.comment.customer`,
     *   `CommentRequestTransfer.comment.customer.companyUserTransfer`, `CommentRequestTransfer.comment.customer.companyUserTransfer.idCompanyUser` transfer properties to be set.
     * - Checks if provided company user has access to comment owner shared cart.
     * - Returns error message when validation failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validate(
        CommentRequestTransfer $commentRequestTransfer,
        CommentValidationResponseTransfer $commentValidationResponseTransfer
    ): CommentValidationResponseTransfer {
        return $this->getFacade()->validateSharedCartComment($commentRequestTransfer, $commentValidationResponseTransfer);
    }
}
