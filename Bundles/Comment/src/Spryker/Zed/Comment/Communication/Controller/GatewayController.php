<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Controller;

use Generated\Shared\Transfer\CommentCollectionTransfer;
use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionCollectionTransfer;
use Generated\Shared\Transfer\CommentVersionFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function createCommentAction(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->createComment($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentAction(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->updateComment($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function reviseCommentAction(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->reviseComment($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function cancelCommentAction(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->cancelComment($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function sendCommentToUserAction(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->sendCommentToUser($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentCollectionTransfer
     */
    public function getCommentCollectionByFilterAction(CommentFilterTransfer $quoteRequestFilterTransfer): CommentCollectionTransfer
    {
        return $this->getFacade()->getCommentCollectionByFilter($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionCollectionTransfer
     */
    public function getCommentVersionCollectionByFilterAction(CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer): CommentVersionCollectionTransfer
    {
        return $this->getFacade()->getCommentVersionCollectionByFilter($quoteRequestVersionFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function getCommentAction(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->getComment($quoteRequestFilterTransfer);
    }
}
