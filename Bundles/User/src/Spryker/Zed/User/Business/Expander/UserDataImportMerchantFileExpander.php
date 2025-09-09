<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Expander;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\User\Business\Reader\UserReaderInterface;

class UserDataImportMerchantFileExpander implements UserDataImportMerchantFileExpanderInterface
{
    /**
     * @param \Spryker\Zed\User\Business\Reader\UserReaderInterface $userReader
     */
    public function __construct(protected UserReaderInterface $userReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): DataImportMerchantFileCollectionTransfer {
        $userIds = $this->extractUserIds($dataImportMerchantFileCollectionTransfer);
        if (!$userIds) {
            return $dataImportMerchantFileCollectionTransfer;
        }

        $indexedUserTransfers = $this->getUserTransfersIndexedByIdUser($userIds);
        foreach ($dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $userTransfer = $indexedUserTransfers[$dataImportMerchantFileTransfer->getIdUserOrFail()] ?? null;
            $dataImportMerchantFileTransfer->setUser($userTransfer);
        }

        return $dataImportMerchantFileCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractUserIds(
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): array {
        $userIds = [];

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $dataImportMerchantFiles */
        $dataImportMerchantFiles = $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles();
        foreach ($dataImportMerchantFiles as $dataImportMerchantFileTransfer) {
            $userIds[] = $dataImportMerchantFileTransfer->getIdUserOrFail();
        }

        return array_unique($userIds);
    }

    /**
     * @param list<int> $userIds
     *
     * @return array<int, \Generated\Shared\Transfer\UserTransfer>
     */
    protected function getUserTransfersIndexedByIdUser(array $userIds): array
    {
        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions((new UserConditionsTransfer())->setUserIds($userIds));

        $indexedUserTransfers = [];
        foreach ($this->userReader->getUserCollection($userCriteriaTransfer)->getUsers() as $userTransfer) {
            $indexedUserTransfers[$userTransfer->getIdUserOrFail()] = $userTransfer;
        }

        return $indexedUserTransfers;
    }
}
