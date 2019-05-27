<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentCollectionTransfer;
use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionCollectionTransfer;
use Generated\Shared\Transfer\CommentVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Comment\Business\CommentBusinessFactory getFactory()
 * @method \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 */
class CommentFacade extends AbstractFacade implements CommentFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function createComment(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentWriter()
            ->createComment($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateComment(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentWriter()
            ->updateComment($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function reviseComment(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentWriter()
            ->reviseComment($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function cancelComment(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentTerminator()
            ->cancelComment($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function closeComment(QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createCommentTerminator()
            ->closeComment($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function sendCommentToUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentSender()
            ->sendCommentToUser($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function createCommentForCompanyUser(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentUserWriter()
            ->createComment($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentForCompanyUser(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentUserWriter()
            ->updateComment($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function reviseCommentForCompanyUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentUserWriter()
            ->reviseComment($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function cancelCommentForCompanyUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentUserTerminator()
            ->cancelComment($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function sendCommentToCompanyUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentUserSender()
            ->sendCommentToCustomer($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentCollectionTransfer
     */
    public function getCommentCollectionByFilter(CommentFilterTransfer $quoteRequestFilterTransfer): CommentCollectionTransfer
    {
        return $this->getFactory()
            ->createCommentReader()
            ->getCommentCollectionByFilter($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionCollectionTransfer
     */
    public function getCommentVersionCollectionByFilter(CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer): CommentVersionCollectionTransfer
    {
        return $this->getFactory()
            ->createCommentReader()
            ->getCommentVersionCollectionByFilter($quoteRequestVersionFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isCommentVersionReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()
            ->createCommentTimeValidator()
            ->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function closeOutdatedComments(): void
    {
        $this->getFactory()
            ->createCommentTerminator()
            ->closeOutdatedComments();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeComment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createCommentVersionSanitizer()
            ->sanitizeComment($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function getComment(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFactory()
            ->createCommentReader()
            ->getComment($quoteRequestFilterTransfer);
    }
}
