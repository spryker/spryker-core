<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Assigner;

use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface;
use Spryker\Zed\PickingList\Business\Updater\PickingListUpdaterInterface;
use Spryker\Zed\PickingList\PickingListConfig;

class PickingListUserAssigner implements PickingListUserAssignerInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @var \Spryker\Zed\PickingList\Business\Updater\PickingListUpdaterInterface
     */
    protected PickingListUpdaterInterface $pickingListUpdater;

    /**
     * @var \Spryker\Zed\PickingList\PickingListConfig
     */
    protected PickingListConfig $pickingListConfig;

    /**
     * @param \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Zed\PickingList\Business\Updater\PickingListUpdaterInterface $pickingListUpdater
     * @param \Spryker\Zed\PickingList\PickingListConfig $pickingListConfig
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListUpdaterInterface $pickingListUpdater,
        PickingListConfig $pickingListConfig
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListUpdater = $pickingListUpdater;
        $this->pickingListConfig = $pickingListConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function unassignPickingListsFromUsers(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer
    {
        $userUuidsToUnassign = $this->extractUserUuidsApplicableForPickingListUnassignment($userCollectionTransfer);
        if ($userUuidsToUnassign === []) {
            return $userCollectionTransfer;
        }

        $pickingListConditionsTransfer = (new PickingListConditionsTransfer())->setUserUuids($userUuidsToUnassign);
        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())->setPickingListConditions($pickingListConditionsTransfer);

        $pickingListCollectionTransfer = $this->pickingListReader->getPickingListCollection($pickingListCriteriaTransfer);
        $pickingListCollectionTransfer = $this->unassignPickingLists($pickingListCollectionTransfer);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->setPickingLists($pickingListCollectionTransfer->getPickingLists())
            ->setIsTransactional(true);

        $this->pickingListUpdater->updatePickingListCollection($pickingListCollectionRequestTransfer);

        return $userCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function unassignPickingLists(PickingListCollectionTransfer $pickingListCollectionTransfer): PickingListCollectionTransfer
    {
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $pickingListTransfer->setUser(null);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractUserUuidsApplicableForPickingListUnassignment(UserCollectionTransfer $userCollectionTransfer): array
    {
        $userUuids = [];
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            if ($this->isUserApplicableForPickingListUnassignment($userTransfer)) {
                $userUuids[] = $userTransfer->getUuidOrFail();
            }
        }

        return array_unique($userUuids);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isUserApplicableForPickingListUnassignment(UserTransfer $userTransfer): bool
    {
        return $userTransfer->getIsWarehouseUser() &&
            in_array(
                $userTransfer->getStatus(),
                $this->pickingListConfig->getUserStatusesApplicableForPickingListUnassignment(),
                true,
            );
    }
}
