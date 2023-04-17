<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\PickingListPushNotification\tests\SprykerTest\Zed\PickingListPushNotification\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PickingListTransfer;
use SprykerTest\Zed\PickingListPushNotification\PickingListPushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group Bundles
 * @group PickingListPushNotification
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group PickingListPushNotification
 * @group Business
 * @group Facade
 * @group PickingListPushNotificationFacadeTest
 * Add your own group annotations below this line
 */
class PickingListPushNotificationFacadeTest extends Unit
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
    public function testCreatePushNotificationCollectionCreatesOnePushNotificationWhenSinglePickingListCollectionGiven(): void
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
    public function testCreatePushNotificationCollectionCreatesTwoPushNotificationWhenThreePickingListWithTwoDifferentWarehouseGiven(): void
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
    public function testCreatePushNotificationCollectionDoesntCreatePushNotificationWhenPickingListWithNoModifiedNotifiableAttributeGiven(): void
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
    public function testCreatePushNotificationCollectionCreatesPushNotificationForNotifiablePickingListsOnlyWhenNotifiableAndNonNotifiablePickingListsGiven(): void
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

    /**
     * @return void
     */
    public function testValidateSubscriptionsShouldReturnNoValidationErrorsWhenUserHasActiveWarehouseUserAssignment(): void
    {
        // Arrange
        $pushNotificationSubscriptionTransfer = $this->tester->createPushNotificationSubscriptionTransfer();

        $this->tester->mockGetWarehouseUserFacadeFactoryMethodWithActiveWarehouseUserAssignmentMock(
            $pushNotificationSubscriptionTransfer,
        );

        // Act
        $errorCollectionTransfer = $this->tester->getFacade()->validateSubscriptions(
            new ArrayObject([$pushNotificationSubscriptionTransfer]),
        );

        // Assert
        $this->assertEmpty($errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateSubscriptionsShouldReturnValidationErrorsWhenUserHasNoWarehouseUserAssignment(): void
    {
        // Arrange
        $pushNotificationSubscriptionTransfer = $this->tester->createPushNotificationSubscriptionTransfer();

        // Assert
        $this->tester->mockGetWarehouseUserFacadeFactoryMethodWithEmptyWarehouseUserAssignmentCollectionMock();

        // Act
        $errorCollectionTransfer = $this->tester->getFacade()->validateSubscriptions(
            new ArrayObject([$pushNotificationSubscriptionTransfer]),
        );

        // Assert
        $this->assertNotEmpty($errorCollectionTransfer->getErrors());
    }
}
