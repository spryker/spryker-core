<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingListPushNotification;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Generated\Shared\Transfer\PushNotificationUserTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeInterface;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeInterface;
use Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig;
use Spryker\Zed\PickingListPushNotification\PickingListPushNotificationDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\PickingListPushNotification\Business\PickingListPushNotificationFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PickingListPushNotificationBusinessTester extends Actor
{
    use _generated\PickingListPushNotificationBusinessTesterActions;

    /**
     * @var string
     */
    public const TEST_PROVIDER_NAME = 'test-provider-name';

    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_SUBSCRIPTION_USER_REFERENCE = 'push-notification-subscription-user-reference-1';

    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_USER_UUID = '48bceecb-cbce-400b-83a2-ee76738c9ddc';

    /**
     * @var string
     */
    protected const STOCK_NAME_1 = 'Stock 1';

    /**
     * @var string
     */
    protected const STOCK_NAME_2 = 'Stock 2';

    /**
     * @var string
     */
    protected const STOCK_UUID_1 = '1f20900b-bb80-49de-a1f4-f148a1d12904';

    /**
     * @var string
     */
    protected const STOCK_UUID_2 = 'ff49cf5a-1d13-44c4-aaf4-3a66b80eb066';

    /**
     * @var int
     */
    protected const PICKING_LIST_ID_1 = 123;

    /**
     * @var int
     */
    protected const PICKING_LIST_ID_2 = 456;

    /**
     * @var int
     */
    protected const PICKING_LIST_ID_3 = 789;

    /**
     * @uses \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig::PUSH_NOTIFICATION_WAREHOUSE_GROUP
     *
     * @var string
     */
    protected const PUSH_NOTIFICATION_WAREHOUSE_GROUP = 'warehouse';

    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_GROUP_IDENTIFIER = '1';

    /**
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function createPushNotificationSubscriptionTransfer(): PushNotificationSubscriptionTransfer
    {
        $pushNotificationUserTransfer = (new PushNotificationUserTransfer())
            ->setReference(static::PUSH_NOTIFICATION_SUBSCRIPTION_USER_REFERENCE)
            ->setUuid(static::PUSH_NOTIFICATION_USER_UUID);

        $pushNotificationGroupTransfer = (new PushNotificationGroupTransfer())
            ->setName(static::PUSH_NOTIFICATION_WAREHOUSE_GROUP)
            ->setIdentifier(static::PUSH_NOTIFICATION_GROUP_IDENTIFIER);

        $pushNotificationSubscriptionTransfer = new PushNotificationSubscriptionTransfer();
        $pushNotificationSubscriptionTransfer->setUser($pushNotificationUserTransfer);
        $pushNotificationSubscriptionTransfer->setGroup($pushNotificationGroupTransfer);

        return $pushNotificationSubscriptionTransfer;
    }

    /**
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer
     */
    public function createPushNotificationCollectionRequestTransferWithNotifiablePickingList(
        string $action
    ): PushNotificationCollectionRequestTransfer {
        $stockTransfer = (new StockTransfer())
            ->setName(static::STOCK_NAME_1)
            ->setUuid(static::STOCK_UUID_1);

        $pickingListTransfer = (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setIdPickingList(static::PICKING_LIST_ID_1)
            ->addModifiedAttribute(PickingListTransfer::STATUS);

        return (new PushNotificationCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setAction($action);
    }

    /**
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer
     */
    public function createPushNotificationCollectionRequestTransferWithNotNotifiablePickingList(
        string $action
    ): PushNotificationCollectionRequestTransfer {
        $stockTransfer = (new StockTransfer())
            ->setName(static::STOCK_NAME_1)
            ->setUuid(static::STOCK_UUID_1);

        $pickingListTransfer = (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setIdPickingList(static::PICKING_LIST_ID_1)
            ->addModifiedAttribute(PickingListTransfer::UUID);

        return (new PushNotificationCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setAction($action);
    }

    /**
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer
     */
    public function createPushNotificationCollectionTransferWithThreeNotifiablePickingList(
        string $action
    ): PushNotificationCollectionRequestTransfer {
        $stock1Transfer = (new StockTransfer())
            ->setName(static::STOCK_NAME_1)
            ->setUuid(static::STOCK_UUID_1);

        $stock2Transfer = (new StockTransfer())
            ->setName(static::STOCK_NAME_2)
            ->setUuid(static::STOCK_UUID_2);

        $pickingList1Transfer = (new PickingListTransfer())
            ->setWarehouse($stock1Transfer)
            ->setIdPickingList(static::PICKING_LIST_ID_1)
            ->addModifiedAttribute(PickingListTransfer::STATUS);
        $pickingList2Transfer = (new PickingListTransfer())
            ->setWarehouse($stock1Transfer)
            ->setIdPickingList(static::PICKING_LIST_ID_2)
            ->addModifiedAttribute(PickingListTransfer::STATUS);
        $pickingList3Transfer = (new PickingListTransfer())
            ->setWarehouse($stock2Transfer)
            ->setIdPickingList(static::PICKING_LIST_ID_3)
            ->addModifiedAttribute(PickingListTransfer::STATUS);

        return (new PushNotificationCollectionRequestTransfer())
            ->addPickingList($pickingList1Transfer)
            ->addPickingList($pickingList2Transfer)
            ->addPickingList($pickingList3Transfer)
            ->setAction($action);
    }

    /**
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer
     */
    public function createPushNotificationCollectionRequestTransferWithNotifiableAndNotNotifiablePickingList(
        string $action
    ): PushNotificationCollectionRequestTransfer {
        $stockTransfer = (new StockTransfer())
            ->setName(static::STOCK_NAME_1)
            ->setUuid(static::STOCK_UUID_1);

        $notifiablePickingListTransfer = (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setIdPickingList(static::PICKING_LIST_ID_1)
            ->addModifiedAttribute(PickingListTransfer::STATUS);

        $notNotifiablePickingList1Transfer = (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setIdPickingList(static::PICKING_LIST_ID_2);

        $notNotifiablePickingList2Transfer = (new PickingListTransfer())
            ->setWarehouse($stockTransfer)
            ->setIdPickingList(static::PICKING_LIST_ID_3)
            ->addModifiedAttribute(PickingListTransfer::UUID);

        return (new PushNotificationCollectionRequestTransfer())
            ->addPickingList($notifiablePickingListTransfer)
            ->addPickingList($notNotifiablePickingList1Transfer)
            ->addPickingList($notNotifiablePickingList2Transfer)
            ->setAction($action);
    }

    /**
     * @return void
     */
    public function mockGetPushNotificationFacadeFactoryMethodWithOnePushNotification(): void
    {
        $pushNotificationGroupTransfer = (new PushNotificationGroupTransfer())
            ->setName(static::PUSH_NOTIFICATION_WAREHOUSE_GROUP)
            ->setIdentifier(static::STOCK_UUID_1);
        $pushNotificationProviderTransfer = (new PushNotificationProviderTransfer())
            ->setName(static::TEST_PROVIDER_NAME);
        $pushNotificationTransfer = (new PushNotificationTransfer())
            ->setGroup($pushNotificationGroupTransfer)
            ->setProvider($pushNotificationProviderTransfer)
            ->setPayload([
                'action' => 'create',
                'entity' => 'picking-lists',
                'ids' => [static::PICKING_LIST_ID_1],
            ]);

        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addPushNotification($pushNotificationTransfer);

        $pushNotificationFacadeBridgeStub = Stub::makeEmpty(
            PickingListPushNotificationToPushNotificationFacadeInterface::class,
            [
                'createPushNotificationCollection' => function () use ($pushNotificationTransfer) {
                    return (new PushNotificationCollectionResponseTransfer())
                        ->addPushNotification($pushNotificationTransfer);
                },
            ],
        );
        $pushNotificationFacadeBridgeStub
            ->expects(new InvokedCountMatcher(1))
            ->method('createPushNotificationCollection')
            ->with($pushNotificationCollectionRequestTransfer);

        $this->getFacade()->setFactory(
            $this->mockFactoryMethod('getPushNotificationFacade', $pushNotificationFacadeBridgeStub)
                ->setConfig($this->mockGetPushNotificationProviderNameConfigMethod()),
        );
    }

    /**
     * @return void
     */
    public function mockGetPushNotificationFacadeFactoryMethodWithTwoPushNotifications(): void
    {
        $pushNotificationGroup1Transfer = (new PushNotificationGroupTransfer())
            ->setName(static::PUSH_NOTIFICATION_WAREHOUSE_GROUP)
            ->setIdentifier(static::STOCK_UUID_1);
        $pushNotificationGroup2Transfer = (new PushNotificationGroupTransfer())
            ->setName(static::PUSH_NOTIFICATION_WAREHOUSE_GROUP)
            ->setIdentifier(static::STOCK_UUID_2);

        $pushNotificationProviderTransfer = (new PushNotificationProviderTransfer())
            ->setName(static::TEST_PROVIDER_NAME);

        $pushNotification1Transfer = (new PushNotificationTransfer())
            ->setGroup($pushNotificationGroup1Transfer)
            ->setProvider($pushNotificationProviderTransfer)
            ->setPayload([
                'action' => 'create',
                'entity' => 'picking-lists',
                'ids' => [static::PICKING_LIST_ID_1, static::PICKING_LIST_ID_2],
            ]);
        $pushNotification2Transfer = (new PushNotificationTransfer())
            ->setGroup($pushNotificationGroup2Transfer)
            ->setProvider($pushNotificationProviderTransfer)
            ->setPayload([
                'action' => 'create',
                'entity' => 'picking-lists',
                'ids' => [static::PICKING_LIST_ID_3],
            ]);

        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addPushNotification($pushNotification1Transfer)
            ->addPushNotification($pushNotification2Transfer);

        $pushNotificationFacadeBridgeStub = Stub::makeEmpty(
            PickingListPushNotificationToPushNotificationFacadeInterface::class,
            [
                'createPushNotificationCollection' => function () use ($pushNotification1Transfer, $pushNotification2Transfer) {
                    return (new PushNotificationCollectionResponseTransfer())
                        ->addPushNotification($pushNotification1Transfer)
                        ->addPushNotification($pushNotification2Transfer);
                },
            ],
        );
        $pushNotificationFacadeBridgeStub
            ->expects(new InvokedCountMatcher(1))
            ->method('createPushNotificationCollection')
            ->with($pushNotificationCollectionRequestTransfer);

        $this->getFacade()->setFactory(
            $this->mockFactoryMethod('getPushNotificationFacade', $pushNotificationFacadeBridgeStub)
                ->setConfig($this->mockGetPushNotificationProviderNameConfigMethod()),
        );
    }

    /**
     * @return void
     */
    public function mockGetPushNotificationFacadeFactoryMethodWhichShouldntBeCalled(): void
    {
        $pushNotificationFacadeBridgeStub = Stub::makeEmpty(
            PickingListPushNotificationToPushNotificationFacadeInterface::class,
        );
        $pushNotificationFacadeBridgeStub
            ->expects(new InvokedCountMatcher(0))
            ->method('createPushNotificationCollection');
        $this->setDependency(
            PickingListPushNotificationDependencyProvider::FACADE_PUSH_NOTIFICATION,
            $pushNotificationFacadeBridgeStub,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function mockGetWarehouseUserFacadeFactoryMethodWithActiveWarehouseUserAssignmentMock(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): void {
        $warehouseUserFacadeBridgeStub = Stub::makeEmpty(
            PickingListPushNotificationToWarehouseUserFacadeInterface::class,
            [
                'getWarehouseUserAssignmentCollection' => function () use ($pushNotificationSubscriptionTransfer) {
                    $warehouseTransfer = (new StockTransfer())
                        ->setUuid($pushNotificationSubscriptionTransfer->getGroupOrFail()->getIdentifierOrFail());
                    $userUuid = $pushNotificationSubscriptionTransfer->getUserOrFail()->getUuidOrFail();

                    $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentTransfer())
                        ->setUserUuid($userUuid)
                        ->setWarehouse($warehouseTransfer)
                        ->setIsActive(true);

                    $warehouseUserAssignmentCollectionTransfer = new WarehouseUserAssignmentCollectionTransfer();
                    $warehouseUserAssignmentCollectionTransfer
                        ->getWarehouseUserAssignments()
                        ->append($warehouseUserAssignmentTransfer);

                    return $warehouseUserAssignmentCollectionTransfer;
                },
            ],
        );
        $this->setDependency(
            PickingListPushNotificationDependencyProvider::FACADE_WAREHOUSE_USER,
            $warehouseUserFacadeBridgeStub,
        );
    }

    /**
     * @return void
     */
    public function mockGetWarehouseUserFacadeFactoryMethodWithEmptyWarehouseUserAssignmentCollectionMock(): void
    {
        $warehouseUserFacadeBridgeStub = Stub::makeEmpty(
            PickingListPushNotificationToWarehouseUserFacadeInterface::class,
            [
                'getWarehouseUserAssignmentCollection' => function () {
                    return new WarehouseUserAssignmentCollectionTransfer();
                },
            ],
        );
        $this->setDependency(
            PickingListPushNotificationDependencyProvider::FACADE_WAREHOUSE_USER,
            $warehouseUserFacadeBridgeStub,
        );
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig
     */
    protected function mockGetPushNotificationProviderNameConfigMethod(): PickingListPushNotificationConfig
    {
        return Stub::make(
            PickingListPushNotificationConfig::class,
            [
                'getPushNotificationProviderName' => static::TEST_PROVIDER_NAME,
            ],
        );
    }
}
