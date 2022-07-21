<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Communication\Plugins\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 * @method \Spryker\Zed\Quote\Business\QuoteFacadeInterface getFacade()
 */
class QuoteCommentValidatorPlugin extends AbstractPlugin implements CommentValidatorPluginInterface
{
    /**
     * @var string
     */
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';

    /**
     * {@inheritDoc}
     * - Checks if `CommentRequestTransfer.ownerType` is "quote".
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
     *   `CommentRequestTransfer.comment.customer.customerReference` transfer properties to be set.
     * - Checks if quote with provided id exists.
     * - Checks if provided customer is an owner of comment owner quote.
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
        return $this->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );
    }
}
