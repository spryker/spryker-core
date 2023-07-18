<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Spryker\Zed\PickingListPushNotification\Business\Grouper\WarehouseUserAssignmentGrouperInterface;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeInterface;

class WarehouseUserAssignmentReader implements WarehouseUserAssignmentReaderInterface
{
    /**
     * @var \Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeInterface
     */
    protected PickingListPushNotificationToWarehouseUserFacadeInterface $warehouseUserFacade;

    /**
     * @var \Spryker\Zed\PickingListPushNotification\Business\Grouper\WarehouseUserAssignmentGrouperInterface
     */
    protected WarehouseUserAssignmentGrouperInterface $warehouseUserAssignmentGrouper;

    /**
     * @param \Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeInterface $warehouseUserFacade
     * @param \Spryker\Zed\PickingListPushNotification\Business\Grouper\WarehouseUserAssignmentGrouperInterface $warehouseUserAssignmentGrouper
     */
    public function __construct(
        PickingListPushNotificationToWarehouseUserFacadeInterface $warehouseUserFacade,
        WarehouseUserAssignmentGrouperInterface $warehouseUserAssignmentGrouper
    ) {
        $this->warehouseUserFacade = $warehouseUserFacade;
        $this->warehouseUserAssignmentGrouper = $warehouseUserAssignmentGrouper;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return array<string, array<string, array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>>
     */
    public function getWarehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): array {
        $warehouseUserAssignmentTransfers = $this->getWarehouseUserAssignmentTransfers(
            $pushNotificationSubscriptionTransfers,
        );

        return $this
            ->warehouseUserAssignmentGrouper
            ->groupWarehouseUserAssignmentTransfersByUserUuidAndWarehouseUuid($warehouseUserAssignmentTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    protected function getWarehouseUserAssignmentTransfers(ArrayObject $pushNotificationSubscriptionTransfers): ArrayObject
    {
        $warehouseUserAssignmentCriteriaTransfer = $this->createWarehouseUserAssignmentCriteriaTransfer(
            $pushNotificationSubscriptionTransfers,
        );

        $warehouseUserAssignmentCollectionTransfer = $this->warehouseUserFacade->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> */
        return $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments();
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCriteriaTransfer(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): WarehouseUserAssignmentCriteriaTransfer {
        $userUuids = [];
        $warehouseUuids = [];
        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $userUuids[] = $pushNotificationSubscriptionTransfer->getUserOrFail()->getUuidOrFail();
            $warehouseUuids[] = $pushNotificationSubscriptionTransfer->getGroupOrFail()->getIdentifierOrFail();
        }

        $warehouseUserAssignmentConditionTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->setUserUuids($userUuids)
            ->setWarehouseUuids($warehouseUuids)
            ->setIsActive(true);

        return (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionTransfer);
    }
}
