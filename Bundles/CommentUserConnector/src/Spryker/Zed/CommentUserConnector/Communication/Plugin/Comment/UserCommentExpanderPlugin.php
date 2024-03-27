<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment;

use Spryker\Zed\CommentExtension\Dependency\Plugin\CommentExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CommentUserConnector\CommentUserConnectorConfig getConfig()
 * @method \Spryker\Zed\CommentUserConnector\Business\CommentUserConnectorFacadeInterface getFacade()
 */
class UserCommentExpanderPlugin extends AbstractPlugin implements CommentExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `CommentTransfer` with `UserTransfer` if `CommentTransfer.fkUser` is set.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function expand(array $commentTransfers): array
    {
        return $this->getFacade()->expandCommentsWithUser($commentTransfers);
    }
}
