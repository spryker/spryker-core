<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingListPushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PickingListTransfer;
use SprykerTest\Zed\PickingListPushNotification\PickingListPushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingListPushNotification
 * @group Business
 * @group Facade
 * @group CreatePushNotificationCollectionTest
 * Add your own group annotations below this line
 */
class CreatePushNotificationCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\PickingListPushNotification\Communication\Plugin\PickingList\PushNotificationPickingListPostCreatePlugin::ACTION_CREATE
     *
     * @var string
     */
    protected const ACTION_CREATE = 'create';

    /**
     * @var \SprykerTest\Zed\PickingListPushNotification\PickingListPushNotificationBusinessTester
     */
    protected PickingListPushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->tester->mockConfigMethod('getPickingListNotifiableAttributes', [PickingListTransfer::STATUS]);
        $this->tester->mockConfigMethod('getPushNotificationProviderName', PickingListPushNotificationBusinessTester::TEST_PROVIDER_NAME);
    }

    /**
     * @return void
     */
    public function testShouldCreateOnePushNotificationWhenSinglePickingListCollectionIsGiven(): void
    {
        // Arrange
        $pushNotificationCollectionRequestTransfer = $this->tester->createPushNotificationCollectionRequestTransferWithNotifiablePickingList(
            static::ACTION_CREATE,
        );

        // Assert
        $this->tester->mockGetPushNotificationFacadeFactoryMethodWithOnePushNotification();

        // Act
        $this->tester->getFacade()->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldCreateTwoPushNotificationsWhenThreePickingListsWithTwoDifferentWarehousesAreGiven(): void
    {
        // Arrange
        $pushNotificationCollectionRequestTransfer = $this
            ->tester
            ->createPushNotificationCollectionTransferWithThreeNotifiablePickingList(static::ACTION_CREATE);

        // Assert
        $this->tester->mockGetPushNotificationFacadeFactoryMethodWithTwoPushNotifications();

        // Act
        $this->tester->getFacade()->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotCreatePushNotificationWhenPickingListWithNoModifiedNotifiableAttributeIsGiven(): void
    {
        // Arrange
        $pushNotificationCollectionRequestTransfer = $this->tester->createPushNotificationCollectionRequestTransferWithNotNotifiablePickingList(
            static::ACTION_CREATE,
        );

        // Assert
        $this->tester->mockGetPushNotificationFacadeFactoryMethodWhichShouldntBeCalled();

        // Act
        $this->tester->getFacade()->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldCreatePushNotificationForNotifiablePickingListsOnlyWhenNotifiableAndNonNotifiablePickingListsAreGiven(): void
    {
        // Arrange
        $pushNotificationCollectionRequestTransfer = $this->tester->createPushNotificationCollectionRequestTransferWithNotifiableAndNotNotifiablePickingList(
            static::ACTION_CREATE,
        );

        // Assert
        $this->tester->mockGetPushNotificationFacadeFactoryMethodWithOnePushNotification();

        // Act
        $this->tester->getFacade()->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }
}
