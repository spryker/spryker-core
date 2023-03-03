<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\PushNotificationFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
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
 * @group PushNotificationFacadeCreatePushNotificationCollectionTest
 * Add your own group annotations below this line
 */
class PushNotificationFacadeCreatePushNotificationCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationProviderExistsValidatorRule::GLOSSARY_KEY_VALIDATION_ERROR_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND
     *
     * @var string
     */
    protected const ERROR_MESSAGE_PROVIDER_NOT_FOUND = 'push_notification.validation.error.push_notification_provider_not_found';

    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatePushNotificationCollectionShouldReturnCreatedPushNotificationCollectionWhenCreationIsSuccessful(): void
    {
        // Arrange
        $pushNotificationTransfers = $this->tester->createValidPushNotifications();
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotifications(new ArrayObject($pushNotificationTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationCollection(
            $pushNotificationCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(
            count($pushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getPushNotifications(),
        );
        $this->assertEmpty($pushNotificationCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationCollectionShouldReturnErrorsWhenInvalidPushNotificationsGiven(): void
    {
        // Arrange
        $pushNotificationTransfers = $this->tester->createInvalidPushNotifications();
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setPushNotifications(new ArrayObject($pushNotificationTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationCollection(
            $pushNotificationCollectionRequestTransfer,
        );

        // Assert
        /**
         * @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
         */
        $errorTransfers = $pushNotificationCollectionResponseTransfer->getErrors();
        $this->assertcount(
            count($pushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getPushNotifications(),
        );
        $this->assertCount(
            count($pushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getErrors(),
        );
        $this->tester->assertErrorCollectionContainsFailedValidationRuleError(
            $errorTransfers,
            static::ERROR_MESSAGE_PROVIDER_NOT_FOUND,
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationCollectionShouldCreateOnlyValidPushNotificationsWhenNonTransactionalModeUsed(): void
    {
        // Arrange
        $validPushNotificationTransfers = $this->tester->createValidPushNotifications();
        $invalidPushNotificationTransfers = $this->tester->createInvalidPushNotifications();
        $pushNotificationTransfers = array_merge($validPushNotificationTransfers, $invalidPushNotificationTransfers);
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setPushNotifications(new ArrayObject($pushNotificationTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationCollection(
            $pushNotificationCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(
            count($validPushNotificationTransfers) + count($invalidPushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getPushNotifications(),
        );
        $this->assertCount(
            count($invalidPushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getErrors(),
        );
        $this->assertCount(
            $this->countPersistedPushNotifications($pushNotificationCollectionResponseTransfer),
            $validPushNotificationTransfers,
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationCollectionShouldNotCreatePushNotificationsWhenValidAndInvalidPushNotificationsGivenInTransactionMode(): void
    {
        // Arrange
        $validPushNotificationTransfers = $this->tester->createValidPushNotifications();
        $invalidPushNotificationTransfers = $this->tester->createInvalidPushNotifications();
        $pushNotificationTransfers = array_merge($validPushNotificationTransfers, $invalidPushNotificationTransfers);
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotifications(new ArrayObject($pushNotificationTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationCollection(
            $pushNotificationCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(
            count($validPushNotificationTransfers) + count($invalidPushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getPushNotifications(),
        );
        $this->assertCount(
            count($invalidPushNotificationTransfers),
            $pushNotificationCollectionResponseTransfer->getErrors(),
        );
        $this->assertSame(
            0,
            $this->countPersistedPushNotifications($pushNotificationCollectionResponseTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer
     *
     * @return int
     */
    protected function countPersistedPushNotifications(
        PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer
    ): int {
        $persistedPushNotificationsCount = 0;
        foreach ($pushNotificationCollectionResponseTransfer->getPushNotifications() as $pushNotificationTransfer) {
            if (!$pushNotificationTransfer->getIdPushNotification()) {
                continue;
            }

            $persistedPushNotificationsCount++;
        }

        return $persistedPushNotificationsCount;
    }
}
