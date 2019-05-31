<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadAction(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        return $this->getFacade()->findCommentThread($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function addCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->addComment($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->updateComment($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentTagsAction(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->updateCommentTags($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function removeCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getFacade()->removeComment($commentRequestTransfer);
    }
}
