<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group Facade
 * @group GetPushNotificationCollectionTest
 * Add your own group annotations below this line
 */
class GetPushNotificationCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const UNKNOWN_PUSH_NOTIFICATION_ID = -1;

    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensurePushNotificationTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyPushNotificationCollection(): void
    {
        // Arrange
        $this->tester->havePushNotification();
        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setPushNotificationIds([static::UNKNOWN_PUSH_NOTIFICATION_ID]);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPushNotificationConditions($pushNotificationConditionsTransfer),
        );

        // Assert
        $this->assertCount(0, $pushNotificationCollectionTransfer->getPushNotifications());
        $this->assertNull($pushNotificationCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionFilteredByPushNotificationIds(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $this->tester->havePushNotification();

        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setPushNotificationIds([$pushNotificationTransfer->getIdPushNotificationOrFail()]);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPushNotificationConditions($pushNotificationConditionsTransfer),
        );

        // Assert
        $this->assertCount(1, $pushNotificationCollectionTransfer->getPushNotifications());

        /** @var \Generated\Shared\Transfer\PushNotificationTransfer $foundPushNotificationTransfer */
        $foundPushNotificationTransfer = $pushNotificationCollectionTransfer->getPushNotifications()->getIterator()->current();
        $this->assertSame($pushNotificationTransfer->getIdPushNotificationOrFail(), $foundPushNotificationTransfer->getIdPushNotificationOrFail());

        $this->assertNull($pushNotificationCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionFilteredByPushNotificationProviderId(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $this->tester->havePushNotification();

        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setPushNotificationProviderIds([$pushNotificationTransfer->getProviderOrFail()->getIdPushNotificationProviderOrFail()]);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPushNotificationConditions($pushNotificationConditionsTransfer),
        );

        // Assert
        $this->assertCount(1, $pushNotificationCollectionTransfer->getPushNotifications());

        /** @var \Generated\Shared\Transfer\PushNotificationTransfer $foundPushNotificationTransfer */
        $foundPushNotificationTransfer = $pushNotificationCollectionTransfer->getPushNotifications()->getIterator()->current();
        $this->assertSame(
            $pushNotificationTransfer->getProviderOrFail()->getIdPushNotificationProviderOrFail(),
            $foundPushNotificationTransfer->getProviderOrFail()->getIdPushNotificationProviderOrFail(),
        );

        $this->assertNull($pushNotificationCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionFilteredByUuid(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $this->tester->havePushNotification();

        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setUuids([$pushNotificationTransfer->getUuidOrFail()]);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPushNotificationConditions($pushNotificationConditionsTransfer),
        );

        // Assert
        $this->assertCount(1, $pushNotificationCollectionTransfer->getPushNotifications());

        /** @var \Generated\Shared\Transfer\PushNotificationTransfer $foundPushNotificationTransfer */
        $foundPushNotificationTransfer = $pushNotificationCollectionTransfer->getPushNotifications()->getIterator()->current();
        $this->assertSame($pushNotificationTransfer->getUuidOrFail(), $foundPushNotificationTransfer->getUuidOrFail());

        $this->assertNull($pushNotificationCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionFilteredByNotificationSent(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $pushNotificationSubscriptionTransfer = $this->tester->havePushNotificationSubscription();
        $this->tester->havePushNotificationSubscriptionDeliveryLog([
            PushNotificationSubscriptionDeliveryLogTransfer::PUSH_NOTIFICATION => $pushNotificationTransfer->toArray(),
            PushNotificationSubscriptionDeliveryLogTransfer::PUSH_NOTIFICATION_SUBSCRIPTION => $pushNotificationSubscriptionTransfer->toArray(),
        ]);

        $notSetPushNotificationTransfer = $this->tester->havePushNotification();

        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setNotificationSent(false);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPushNotificationConditions($pushNotificationConditionsTransfer),
        );

        // Assert
        $this->assertCount(1, $pushNotificationCollectionTransfer->getPushNotifications());

        /** @var \Generated\Shared\Transfer\PushNotificationTransfer $foundPushNotificationTransfer */
        $foundPushNotificationTransfer = $pushNotificationCollectionTransfer->getPushNotifications()->getIterator()->current();
        $this->assertSame($notSetPushNotificationTransfer->getIdPushNotificationOrFail(), $foundPushNotificationTransfer->getIdPushNotificationOrFail());

        $this->assertNull($pushNotificationCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionByLimitAndOffset(): void
    {
        // Arrange
        for ($i = 0; $i < 4; $i++) {
            $this->tester->havePushNotification();
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(1)
            ->setLimit(2);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPagination($paginationTransfer),
        );

        // Assert
        $this->assertCount(2, $pushNotificationCollectionTransfer->getPushNotifications());
        $this->assertNotNull($pushNotificationCollectionTransfer->getPagination());
        $this->assertSame(4, $pushNotificationCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionByPagination(): void
    {
        // Arrange
        for ($i = 0; $i < 7; $i++) {
            $this->tester->havePushNotification();
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->setPagination($paginationTransfer),
        );

        // Assert
        $this->assertCount(2, $pushNotificationCollectionTransfer->getPushNotifications());
        $this->assertNotNull($pushNotificationCollectionTransfer->getPagination());

        $paginationTransfer = $pushNotificationCollectionTransfer->getPaginationOrFail();

        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(7, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(3, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(4, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(4, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(3, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionSortedByAsc(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $secondPushNotificationTransfer = $this->tester->havePushNotification();
        $thirdPushNotificationTransfer = $this->tester->havePushNotification();

        $sortTransfer = (new SortTransfer())
            ->setField(PushNotificationTransfer::ID_PUSH_NOTIFICATION)
            ->setIsAscending(true);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->addSort($sortTransfer),
        );

        // Assert
        $this->assertCount(3, $pushNotificationCollectionTransfer->getPushNotifications());
        $this->assertSame(
            $pushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationCollectionTransfer->getPushNotifications()->offsetGet(0)->getIdPushNotificationOrFail(),
        );
        $this->assertSame(
            $secondPushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationCollectionTransfer->getPushNotifications()->offsetGet(1)->getIdPushNotificationOrFail(),
        );
        $this->assertSame(
            $thirdPushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationCollectionTransfer->getPushNotifications()->offsetGet(2)->getIdPushNotificationOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationCollectionSortedByDesc(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $secondPushNotificationTransfer = $this->tester->havePushNotification();
        $thirdPushNotificationTransfer = $this->tester->havePushNotification();

        $sortTransfer = (new SortTransfer())
            ->setField(PushNotificationTransfer::ID_PUSH_NOTIFICATION)
            ->setIsAscending(false);

        // Act
        $pushNotificationCollectionTransfer = $this->tester->getFacade()->getPushNotificationCollection(
            (new PushNotificationCriteriaTransfer())->addSort($sortTransfer),
        );

        // Assert
        $this->assertCount(3, $pushNotificationCollectionTransfer->getPushNotifications());
        $this->assertSame(
            $thirdPushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationCollectionTransfer->getPushNotifications()->offsetGet(0)->getIdPushNotificationOrFail(),
        );
        $this->assertSame(
            $secondPushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationCollectionTransfer->getPushNotifications()->offsetGet(1)->getIdPushNotificationOrFail(),
        );
        $this->assertSame(
            $pushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationCollectionTransfer->getPushNotifications()->offsetGet(2)->getIdPushNotificationOrFail(),
        );
    }
}
