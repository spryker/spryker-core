<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group Facade
 * @group DeleteExpiredPushNotificationSubscriptionsTest
 * Add your own group annotations below this line
 */
class DeleteExpiredPushNotificationSubscriptionsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeletePushNotificationSubscriptionCollectionShouldDeleteOnlyExpiredPushNotificationSubscriptionsWhenIsExpiredFlagGiven(): void
    {
        // Arrange
        $expiredPushNotificationSubscriptionTransfer = $this->tester->createExpiredPushNotificationSubscription();
        $actualPushNotificationSubscriptionTransfer = $this->tester->createActualPushNotificationSubscription();
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationFacade->deletePushNotificationSubscriptionCollection(
            (new PushNotificationSubscriptionCollectionDeleteCriteriaTransfer())
                ->setIsExpired(true),
        );

        // Assert
        $this->assertNull(
            $this->tester->findPushNotificationSubscriptionEntityById(
                $expiredPushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
            ),
        );

        $this->assertNotNull(
            $this->tester->findPushNotificationSubscriptionEntityById(
                $actualPushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
            ),
        );
    }

    /**
     * @return void
     */
    public function testDeletePushNotificationSubscriptionCollectionShouldDeleteAllPushNotificationSubscriptionsWhenNoIsExpiredFlagGiven(): void
    {
        // Arrange
        $expiredPushNotificationSubscriptionTransfer = $this->tester->createExpiredPushNotificationSubscription();
        $actualPushNotificationSubscriptionTransfer = $this->tester->createActualPushNotificationSubscription();
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationFacade->deletePushNotificationSubscriptionCollection(
            new PushNotificationSubscriptionCollectionDeleteCriteriaTransfer(),
        );

        // Assert
        $this->assertNull(
            $this->tester->findPushNotificationSubscriptionEntityById(
                $expiredPushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
            ),
        );

        $this->assertNull(
            $this->tester->findPushNotificationSubscriptionEntityById(
                $actualPushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
            ),
        );
    }
}
