<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader;

use Generated\Shared\Transfer\CommentsRequestTransfer;
use Spryker\Zed\CommentMerchantRelationshipConnector\Dependency\Facade\CommentMerchantRelationshipConnectorToCommentFacadeInterface;

class CommentReader implements CommentReaderInterface
{
    /**
     * @var \Spryker\Zed\CommentMerchantRelationshipConnector\Dependency\Facade\CommentMerchantRelationshipConnectorToCommentFacadeInterface
     */
    protected CommentMerchantRelationshipConnectorToCommentFacadeInterface $commentFacade;

    /**
     * @param \Spryker\Zed\CommentMerchantRelationshipConnector\Dependency\Facade\CommentMerchantRelationshipConnectorToCommentFacadeInterface $commentFacade
     */
    public function __construct(
        CommentMerchantRelationshipConnectorToCommentFacadeInterface $commentFacade
    ) {
        $this->commentFacade = $commentFacade;
    }

    /**
     * @param string $ownerType
     * @param list<int> $ownerIds
     *
     * @return array<int, \Generated\Shared\Transfer\CommentThreadTransfer>
     */
    public function getCommentThreadsIndexedByOwnerId(string $ownerType, array $ownerIds): array
    {
        $commentThreadTransfers = $this->commentFacade->getCommentThreads(
            (new CommentsRequestTransfer())->setOwnerType($ownerType)->setOwnerIds($ownerIds),
        );

        $indexedCommentThreadTransfers = [];
        foreach ($commentThreadTransfers as $commentThreadTransfer) {
            $indexedCommentThreadTransfers[$commentThreadTransfer->getOwnerIdOrFail()] = $commentThreadTransfer;
        }

        return $indexedCommentThreadTransfers;
    }
}
