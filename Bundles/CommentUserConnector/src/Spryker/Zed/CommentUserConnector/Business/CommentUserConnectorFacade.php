<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Business;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CommentUserConnector\Business\CommentUserConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\CommentUserConnector\Persistence\CommentUserConnectorRepositoryInterface getRepository()
 */
class CommentUserConnectorFacade extends AbstractFacade implements CommentUserConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function expandCommentsWithUser(array $commentTransfers): array
    {
        return $this->getFactory()
            ->createCommentExpander()
            ->expandCommentsWithUser($commentTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentAuthor(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer
    {
        return $this->getFactory()
            ->createCommentValidator()
            ->validateCommentAuthor($commentRequestTransfer);
    }
}
