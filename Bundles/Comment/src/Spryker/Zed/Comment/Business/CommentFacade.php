<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
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
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        return $this->getFactory()
            ->createCommentReader()
            ->findCommentThread($commentRequestTransfer);
    }
}
