<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingListPushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use SprykerTest\Zed\PickingListPushNotification\PickingListPushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingListPushNotification
 * @group Business
 * @group Facade
 * @group ValidateSubscriptionsTest
 * Add your own group annotations below this line
 */
class ValidateSubscriptionsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PickingListPushNotification\PickingListPushNotificationBusinessTester
     */
    protected PickingListPushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnNoValidationErrorsWhenUserHasActiveWarehouseUserAssignment(): void
    {
        // Arrange
        $pushNotificationSubscriptionTransfer = $this->tester->createPushNotificationSubscriptionTransfer();

        $this->tester->mockGetWarehouseUserFacadeFactoryMethodWithActiveWarehouseUserAssignmentMock(
            $pushNotificationSubscriptionTransfer,
        );

        // Act
        $errorCollectionTransfer = $this->tester->getFacade()->validateSubscriptions(
            (new PushNotificationSubscriptionCollectionTransfer())->addPushNotificationSubscription($pushNotificationSubscriptionTransfer),
        );

        // Assert
        $this->assertEmpty($errorCollectionTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnValidationErrorsWhenUserHasNoWarehouseUserAssignment(): void
    {
        // Arrange
        $pushNotificationSubscriptionTransfer = $this->tester->createPushNotificationSubscriptionTransfer();

        // Assert
        $this->tester->mockGetWarehouseUserFacadeFactoryMethodWithEmptyWarehouseUserAssignmentCollectionMock();

        // Act
        $errorCollectionTransfer = $this->tester->getFacade()->validateSubscriptions(
            (new PushNotificationSubscriptionCollectionTransfer())->addPushNotificationSubscription($pushNotificationSubscriptionTransfer),
        );

        // Assert
        $this->assertNotEmpty($errorCollectionTransfer->getErrors());
    }
}
