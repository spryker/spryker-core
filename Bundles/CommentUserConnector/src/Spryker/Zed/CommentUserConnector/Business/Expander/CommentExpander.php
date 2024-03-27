<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Business\Expander;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Spryker\Zed\CommentUserConnector\Business\Reader\UserReaderInterface;

class CommentExpander implements CommentExpanderInterface
{
    /**
     * @var \Spryker\Zed\CommentUserConnector\Business\Reader\UserReaderInterface
     */
    protected UserReaderInterface $userReader;

    /**
     * @param \Spryker\Zed\CommentUserConnector\Business\Reader\UserReaderInterface $userReader
     */
    public function __construct(UserReaderInterface $userReader)
    {
        $this->userReader = $userReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function expandCommentsWithUser(array $commentTransfers): array
    {
        $userIds = $this->extractUserIdsFromCommentTransfers($commentTransfers);
        $userCollectionTransfer = $this->userReader->getUserCollectionByUserIds($userIds);
        $userTransfersIndexedByIdUser = $this->getUserTransfersIndexedByIdUser($userCollectionTransfer);

        foreach ($commentTransfers as $commentTransfer) {
            $idUser = $commentTransfer->getFkUser();
            if (!$idUser || !isset($userTransfersIndexedByIdUser[$idUser])) {
                continue;
            }

            $commentTransfer->setUser($userTransfersIndexedByIdUser[$idUser]);
        }

        return $commentTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return array<int, int>
     */
    protected function extractUserIdsFromCommentTransfers(array $commentTransfers): array
    {
        $userIds = [];
        foreach ($commentTransfers as $commentTransfer) {
            if ($commentTransfer->getFkUser() !== null) {
                $userIds[] = $commentTransfer->getFkUserOrFail();
            }
        }

        return array_unique($userIds);
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\UserTransfer>
     */
    protected function getUserTransfersIndexedByIdUser(UserCollectionTransfer $userCollectionTransfer): array
    {
        $indexedUserTransfers = [];
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            $indexedUserTransfers[$userTransfer->getIdUserOrFail()] = $userTransfer;
        }

        return $indexedUserTransfers;
    }
}
