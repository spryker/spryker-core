<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\PushNotificationFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group PushNotificationFacade
 * @group Facade
 * @group PushNotificationFacadeGetPushNotificationProviderCollectionTest
 * Add your own group annotations below this line
 */
class PushNotificationFacadeGetPushNotificationProviderCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetPushNotificationProviderCollectionShouldReturnPushNotificationProviderCollectionWhenEmptyCriteriaGiven(): void
    {
        // Arrange
        $this->tester->havePushNotificationProvider();
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationProviderCollectionTransfer = $pushNotificationFacade->getPushNotificationProviderCollection(
            new PushNotificationProviderCriteriaTransfer(),
        );

        // Assert
        $this->assertNotEmpty($pushNotificationProviderCollectionTransfer->getProviders());
    }

    /**
     * @return void
     */
    public function testGetPushNotificationProviderCollectionShouldReturnFilteredPushNotificationProviderCollectionWhenCriteriaWithProviderNameGiven(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = $this->tester->havePushNotificationProvider();
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addName($pushNotificationProviderTransfer->getName());
        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions(
                $pushNotificationProviderConditionsTransfer,
            );

        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationProviderCollectionTransfer = $pushNotificationFacade->getPushNotificationProviderCollection(
            $pushNotificationProviderCriteriaTransfer,
        );

        // Assert
        /** @var \Generated\Shared\Transfer\PushNotificationProviderTransfer $responsePushNotificationProviderTransfer */
        $responsePushNotificationProviderTransfer = $pushNotificationProviderCollectionTransfer->getProviders()->offsetGet(0);
        $this->assertSame(
            $pushNotificationProviderTransfer->getNameOrFail(),
            $responsePushNotificationProviderTransfer->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testGetPushNotificationProviderCollectionShouldReturnAscendantSortedPushNotificationProviderCollectionWhenCriteriaWithAscendantSortGiven(): void
    {
        // Arrange
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL,
            ],
        );
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE,
            ],
        );
        $pushNotificationFacade = $this->tester->getFacade();
        $pushNotificationProviderCriteriaTransfer = $this
            ->tester
            ->createPushNotificationProviderCriteriaTransferWithSort(true);

        // Act
        $pushNotificationProviderCollectionTransfer = $pushNotificationFacade->getPushNotificationProviderCollection(
            $pushNotificationProviderCriteriaTransfer,
        );

        // Assert
        $firstPushNotificationProviderTransfer = $pushNotificationProviderCollectionTransfer->getProviders()->offsetGet(0);
        $this->assertSame(
            PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL,
            $firstPushNotificationProviderTransfer->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testGetPushNotificationProviderCollectionShouldReturnDescendantSortedPushNotificationProviderCollectionWhenCriteriaWithDescendantSortGiven(): void
    {
        // Arrange
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL,
            ],
        );
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE,
            ],
        );
        $pushNotificationFacade = $this->tester->getFacade();
        $pushNotificationProviderCriteriaTransfer = $this
            ->tester
            ->createPushNotificationProviderCriteriaTransferWithSort(false);

        // Act
        $pushNotificationProviderCollectionTransfer = $pushNotificationFacade->getPushNotificationProviderCollection(
            $pushNotificationProviderCriteriaTransfer,
        );

        // Assert
        $firstPushNotificationProviderTransfer = $pushNotificationProviderCollectionTransfer->getProviders()->offsetGet(0);
        $this->assertSame(
            PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE,
            $firstPushNotificationProviderTransfer->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testGetPushNotificationProviderCollectionShouldReturnPaginatedPushNotificationProviderCollectionWhenCriteriaWithLimitAndOffsetGiven(): void
    {
        // Arrange
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL,
            ],
        );
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE,
            ],
        );
        $pushNotificationFacade = $this->tester->getFacade();
        $pushNotificationProviderCriteriaTransfer = $this
            ->tester
            ->createPushNotificationProviderCriteriaTransferWithPagination(1, 1);

        // Act
        $pushNotificationProviderCollectionTransfer = $pushNotificationFacade->getPushNotificationProviderCollection(
            $pushNotificationProviderCriteriaTransfer,
        );

        // Assert
        $this->assertCount(
            1,
            $pushNotificationProviderCollectionTransfer->getProviders(),
        );
    }

    /**
     * @return void
     */
    public function testGetPushNotificationProviderCollectionShouldReturnPaginatedPushNotificationProviderCollectionWhenCriteriaWithPageAndMaxPerPageGiven(): void
    {
        // Arrange
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL,
            ],
        );
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationBusinessTester::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE,
            ],
        );
        $pushNotificationFacade = $this->tester->getFacade();
        $pushNotificationProviderCriteriaTransfer = $this
            ->tester
            ->createPushNotificationProviderCriteriaTransferWithPagination(null, null, 2, 1);

        // Act
        $pushNotificationProviderCollectionTransfer = $pushNotificationFacade->getPushNotificationProviderCollection(
            $pushNotificationProviderCriteriaTransfer,
        );

        // Assert
        $this->assertCount(
            1,
            $pushNotificationProviderCollectionTransfer->getProviders(),
        );
    }
}
