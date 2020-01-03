<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector\Dependency\Facade;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;

class CommentSalesConnectorToCommentFacadeBridge implements CommentSalesConnectorToCommentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Comment\Business\CommentFacadeInterface
     */
    protected $commentFacade;

    /**
     * @param \Spryker\Zed\Comment\Business\CommentFacadeInterface $commentFacade
     */
    public function __construct($commentFacade)
    {
        $this->commentFacade = $commentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOwner(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        return $this->commentFacade->findCommentThreadByOwner($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $commentFilterTransfer
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function duplicateCommentThread(CommentFilterTransfer $commentFilterTransfer, CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->commentFacade->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }
}
