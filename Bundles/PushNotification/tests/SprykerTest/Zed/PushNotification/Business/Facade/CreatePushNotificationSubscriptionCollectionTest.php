<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group Facade
 * @group CreatePushNotificationSubscriptionCollectionTest
 * Add your own group annotations below this line
 */
class CreatePushNotificationSubscriptionCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationProviderExistsValidatorRule::GLOSSARY_KEY_VALIDATION_ERROR_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND
     *
     * @var string
     */
    protected const ERROR_MESSAGE_PROVIDER_NOT_FOUND = 'push_notification.validation.error.push_notification_provider_not_found';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionGroupNameAllowedValidatorRule::GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_WRONG_GROUP_NAME = 'push_notification.validation.error.wrong_group_name';

    /**
     * @var string
     */
    protected const ALLOWED_GROUP_NAME = 'ALLOWED_GROUP_NAME';

    /**
     * @var string
     */
    protected const DISALLOWED_GROUP_NAME = 'DISALLOWED_GROUP_NAME';

    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatePushNotificationSubscriptionCollectionShouldReturnCreatedPushNotificationSubscriptionCollectionWhenCreationIsSuccessful(): void
    {
        // Arrange
        $pushNotificationSubscriptionTransfers = $this->tester->createValidPushNotificationSubscriptions();
        $pushNotificationSubscriptionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotificationSubscriptions(new ArrayObject($pushNotificationSubscriptionTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionResponseTransfer = $pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionRequestTransfer);

        // Assert
        $this->assertCount(
            count($pushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionResponseTransfer->getPushNotificationSubscriptions(),
        );
        $this->assertEmpty($pushNotificationSubscriptionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationSubscriptionCollectionShouldReturnValidationErrorWhenInvalidProviderNameGiven(): void
    {
        // Arrange
        $pushNotificationSubscriptionTransfers = $this->tester->createInvalidPushNotificationSubscriptions();
        $pushNotificationSubscriptionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setPushNotificationSubscriptions(new ArrayObject($pushNotificationSubscriptionTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionResponseTransfer = $pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionRequestTransfer);

        // Assert
        /**
         * @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
         */
        $errorTransfers = $pushNotificationSubscriptionResponseTransfer->getErrors();
        $this->assertCount(
            count($pushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionResponseTransfer->getErrors(),
        );
        $this->tester->assertErrorCollectionContainsFailedValidationRuleError(
            $errorTransfers,
            static::ERROR_MESSAGE_PROVIDER_NOT_FOUND,
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationSubscriptionCollectionShouldReturnValidationErrorWhenGroupNameIsNotAllowed(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getGroupNameAllowList', [static::ALLOWED_GROUP_NAME]);

        $pushNotificationSubscriptionTransfer = $this->tester->createPushNotificationSubscriptionTransfer([], [], []);
        $pushNotificationSubscriptionTransfer->getGroupOrFail()->setName(static::DISALLOWED_GROUP_NAME);
        $pushNotificationSubscriptionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addPushNotificationSubscription($pushNotificationSubscriptionTransfer);
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionResponseTransfer = $pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionRequestTransfer);

        // Assert
        /**
         * @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
         */
        $errorTransfers = $pushNotificationSubscriptionResponseTransfer->getErrors();
        $this->tester->assertErrorCollectionContainsFailedValidationRuleError(
            $errorTransfers,
            static::GLOSSARY_KEY_ERROR_WRONG_GROUP_NAME,
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationSubscriptionCollectionShouldCreateOnlyValidPushNotificationSubscriptionsWhenNonTransactionalModeUsed(): void
    {
        // Arrange
        $validPushNotificationSubscriptionTransfers = $this->tester->createValidPushNotificationSubscriptions();
        $invalidPushNotificationSubscriptionTransfers = $this->tester->createInvalidPushNotificationSubscriptions();
        $pushNotificationSubscriptionTransfers = array_merge(
            $validPushNotificationSubscriptionTransfers,
            $invalidPushNotificationSubscriptionTransfers,
        );
        $pushNotificationSubscriptionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setPushNotificationSubscriptions(new ArrayObject($pushNotificationSubscriptionTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionResponseTransfer = $pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionRequestTransfer);

        // Assert
        $this->assertCount(
            count($validPushNotificationSubscriptionTransfers) + count($invalidPushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionResponseTransfer->getPushNotificationSubscriptions(),
        );
        $this->assertCount(
            count($invalidPushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionResponseTransfer->getErrors(),
        );
        $this->assertCount(
            $this->countPersistedPushNotificationSubscriptions($pushNotificationSubscriptionResponseTransfer),
            $validPushNotificationSubscriptionTransfers,
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationSubscriptionCollectionShouldNotCreatePushNotificationSubscriptionsWhenValidAndInvalidPushNotificationSubscriptionsGivenInTransactionMode(): void
    {
        // Arrange
        $validPushNotificationSubscriptionTransfers = $this->tester->createValidPushNotificationSubscriptions();
        $invalidPushNotificationSubscriptionTransfers = $this->tester->createInvalidPushNotificationSubscriptions();
        $pushNotificationSubscriptionTransfers = array_merge(
            $validPushNotificationSubscriptionTransfers,
            $invalidPushNotificationSubscriptionTransfers,
        );
        $pushNotificationSubscriptionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotificationSubscriptions(new ArrayObject($pushNotificationSubscriptionTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionResponseTransfer = $pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionRequestTransfer);

        // Assert
        $this->assertCount(
            count($validPushNotificationSubscriptionTransfers) + count($invalidPushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionResponseTransfer->getPushNotificationSubscriptions(),
        );
        $this->assertCount(
            count($invalidPushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionResponseTransfer->getErrors(),
        );
        $this->assertSame(
            0,
            $this->countPersistedPushNotificationSubscriptions($pushNotificationSubscriptionResponseTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer $pushNotificationSubscriptionCollectionResponseTransfer
     *
     * @return int
     */
    protected function countPersistedPushNotificationSubscriptions(
        PushNotificationSubscriptionCollectionResponseTransfer $pushNotificationSubscriptionCollectionResponseTransfer
    ): int {
        $persistedPushNotificationSubscriptionsCount = 0;
        foreach ($pushNotificationSubscriptionCollectionResponseTransfer->getPushNotificationSubscriptions() as $pushNotificationSubscriptionTransfer) {
            if (!$pushNotificationSubscriptionTransfer->getIdPushNotificationSubscription()) {
                continue;
            }

            $persistedPushNotificationSubscriptionsCount++;
        }

        return $persistedPushNotificationSubscriptionsCount;
    }
}
