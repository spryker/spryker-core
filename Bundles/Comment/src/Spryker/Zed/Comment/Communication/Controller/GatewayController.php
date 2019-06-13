<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->addComment($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->updateComment($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateCommentTagsAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->updateCommentTags($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->removeComment($commentRequestTransfer);
    }
}
