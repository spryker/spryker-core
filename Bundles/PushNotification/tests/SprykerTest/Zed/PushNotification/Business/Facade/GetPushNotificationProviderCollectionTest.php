<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
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
 * @group GetPushNotificationProviderCollectionTest
 * Add your own group annotations below this line
 */
class GetPushNotificationProviderCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const UNKNOWN_PUSH_NOTIFICATION_PROVIDER_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @var int
     */
    protected const NUMBER_OF_PUSH_NOTIFICATION_PROVIDERS = 5;

    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @var list<\Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    protected array $pushNotificationProviderTransfers;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensurePushNotificationTablesAreEmpty();
        $this->pushNotificationProviderTransfers = $this->createDummyPushNotificationProviderTransfers();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyPushNotificationProviderCollection(): void
    {
        // Arrange
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addUuid(static::UNKNOWN_PUSH_NOTIFICATION_PROVIDER_UUID);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            0,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNull($pushNotificationProviderCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionByUuids(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = $this->pushNotificationProviderTransfers[0];

        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addUuid($pushNotificationProviderTransfer->getUuidOrFail());

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNull($pushNotificationProviderCollectionTransfer->getPagination());

        $this->assertSame(
            $pushNotificationProviderTransfer->getUuidOrFail(),
            $pushNotificationProviderCollectionTransfer
                ->getPushNotificationProviders()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionByUuidsInversed(): void
    {
        // Arrange
        $pushNotificationProviderTransferToExclude = $this->pushNotificationProviderTransfers[0];
        $pushNotificationProviderTransferExpected = $this->pushNotificationProviderTransfers[1];

        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addUuid($pushNotificationProviderTransferToExclude->getUuidOrFail())
            ->setIsUuidsConditionInversed(true);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            4,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNull($pushNotificationProviderCollectionTransfer->getPagination());

        $this->assertSame(
            $pushNotificationProviderTransferExpected->getUuidOrFail(),
            $pushNotificationProviderCollectionTransfer
                ->getPushNotificationProviders()
                ->getIterator()
                ->current()
                ->getUuidOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionByNames(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = $this->pushNotificationProviderTransfers[0];

        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addName($pushNotificationProviderTransfer->getNameOrFail());

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            1,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNull($pushNotificationProviderCollectionTransfer->getPagination());

        $this->assertSame(
            $pushNotificationProviderTransfer->getNameOrFail(),
            $pushNotificationProviderCollectionTransfer
                ->getPushNotificationProviders()
                ->getIterator()
                ->current()
                ->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionPaginatedByOffsetAndLimit(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit(2);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNotNull($pushNotificationProviderCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_PUSH_NOTIFICATION_PROVIDERS,
            $pushNotificationProviderCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionPaginatedByPage(): void
    {
        // Arrange
        $paginationTransfer = (new PaginationTransfer())->setPage(2)->setMaxPerPage(2);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNotNull($pushNotificationProviderCollectionTransfer->getPagination());

        $this->assertSame(
            static::NUMBER_OF_PUSH_NOTIFICATION_PROVIDERS,
            $pushNotificationProviderCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $sortTransfer = (new SortTransfer())
            ->setField(PushNotificationProviderTransfer::ID_PUSH_NOTIFICATION_PROVIDER)
            ->setIsAscending(true);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_PUSH_NOTIFICATION_PROVIDERS,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNull($pushNotificationProviderCollectionTransfer->getPagination());

        foreach ($this->pushNotificationProviderTransfers as $offset => $pushNotificationProviderTransfer) {
            $this->assertSame(
                $pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail(),
                $pushNotificationProviderCollectionTransfer->getPushNotificationProviders()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdPushNotificationProviderOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnPushNotificationProviderCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $pushNotificationProviderTransfers = array_reverse($this->pushNotificationProviderTransfers);

        $sortTransfer = (new SortTransfer())
            ->setField(PushNotificationProviderTransfer::ID_PUSH_NOTIFICATION_PROVIDER)
            ->setIsAscending(false);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $pushNotificationProviderCollectionTransfer = $this->tester
            ->getFacade()
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::NUMBER_OF_PUSH_NOTIFICATION_PROVIDERS,
            $pushNotificationProviderCollectionTransfer->getPushNotificationProviders(),
        );

        $this->assertNull($pushNotificationProviderCollectionTransfer->getPagination());

        foreach ($pushNotificationProviderTransfers as $offset => $pushNotificationProviderTransfer) {
            $this->assertSame(
                $pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail(),
                $pushNotificationProviderCollectionTransfer->getPushNotificationProviders()
                    ->getIterator()
                    ->offsetGet($offset)
                    ->getIdPushNotificationProviderOrFail(),
            );
        }
    }

    /**
     * @return list<\Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    protected function createDummyPushNotificationProviderTransfers(): array
    {
        $pushNotificationProviderTransfers = [];
        for ($i = 0; $i < static::NUMBER_OF_PUSH_NOTIFICATION_PROVIDERS; $i++) {
            $pushNotificationProviderTransfers[] = $this->tester->havePushNotificationProvider();
        }

        return $pushNotificationProviderTransfers;
    }
}
