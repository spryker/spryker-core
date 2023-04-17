<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Zed\PickingListPushNotification\Business\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig;

class PushNotificationSubscriptionWarehouseUserAssignmentValidator implements PushNotificationSubscriptionWarehouseUserAssignmentValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND = 'picking_list_push_notification.validation.warehouse_entity_not_found';

    /**
     * @var \Spryker\Zed\PickingListPushNotification\Business\Reader\WarehouseUserAssignmentReaderInterface
     */
    protected WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader;

    /**
     * @var \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig
     */
    protected PickingListPushNotificationConfig $pickingListPushNotificationConfig;

    /**
     * @param \Spryker\Zed\PickingListPushNotification\Business\Reader\WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader
     * @param \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig $pickingListPushNotificationConfig
     */
    public function __construct(
        WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader,
        PickingListPushNotificationConfig $pickingListPushNotificationConfig
    ) {
        $this->warehouseUserAssignmentReader = $warehouseUserAssignmentReader;
        $this->pickingListPushNotificationConfig = $pickingListPushNotificationConfig;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(ArrayObject $pushNotificationSubscriptionTransfers): ErrorCollectionTransfer
    {
        $warehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid = $this
            ->warehouseUserAssignmentReader
            ->getWarehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid(
                $pushNotificationSubscriptionTransfers,
            );
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            if (!$this->isApplicable($pushNotificationSubscriptionTransfer)) {
                continue;
            }

            if ($this->isAssigned($pushNotificationSubscriptionTransfer, $warehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid)) {
                continue;
            }

            $errorTransfer = $this->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND,
            );
            $errorCollectionTransfer->getErrors()->append($errorTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return bool
     */
    protected function isApplicable(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): bool
    {
        $pushNotificationGroupTransfer = $pushNotificationSubscriptionTransfer->getGroupOrFail();

        $pushNotificationGroupName = $pushNotificationGroupTransfer->getNameOrFail();

        return $pushNotificationGroupName === $this->pickingListPushNotificationConfig->getPushNotificationWarehouseGroup();
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param array<string, array<string, array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>> $warehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid
     *
     * @return bool
     */
    protected function isAssigned(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        array $warehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid
    ): bool {
        $userUuid = $pushNotificationSubscriptionTransfer->getUserOrFail()->getUuidOrFail();
        $warehouseUuid = $pushNotificationSubscriptionTransfer->getGroupOrFail()->getIdentifierOrFail();

        return isset($warehouseUserAssignmentTransfersGroupedByUserUuidAndWarehouseUuid[$userUuid][$warehouseUuid]);
    }

    /**
     * @param string $entityIdentifier
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(string $entityIdentifier, string $message): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage($message);
    }
}
