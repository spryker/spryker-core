<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group Facade
 * @group DeletePushNotificationProviderCollectionTest
 * Add your own group annotations below this line
 */
class DeletePushNotificationProviderCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationSubscriptionExistsPushNotificationProviderValidatorRule
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS = 'push_notification.validation.push_notification_subscription_exists';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationExistsPushNotificationProviderValidatorRule
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS = 'push_notification.validation.push_notification_exists';

    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_PROVIDER_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

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
    public function testShouldDeletePushNotificationProviderCollectionByPushNotificationProviderUuids(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = $this->tester->havePushNotificationProvider();
        $pushNotificationProviderCollectionDeleteCriteriaTransfer = (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->addUuid($pushNotificationProviderTransfer->getUuidOrFail())
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $pushNotificationProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->getPushNotificationProviderQuery()->count());

        /** @var \Generated\Shared\Transfer\PushNotificationProviderTransfer $persistedPushNotificationProviderTransfer */
        $deletedPushNotificationProviderTransfer = $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders()->getIterator()->current();

        $this->assertEquals($pushNotificationProviderTransfer, $deletedPushNotificationProviderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateExistenceAmongPersistedPushNotifications(): void
    {
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $pushNotificationProviderTransfer = $pushNotificationTransfer->getProvider();
        $pushNotificationProviderCollectionDeleteCriteriaTransfer = (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->addUuid($pushNotificationProviderTransfer->getUuidOrFail())
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateExistenceAmongPersistedPushNotificationSubscriptions(): void
    {
        $pushNotificationSubscriptionTransfer = $this->tester->havePushNotificationSubscription();
        $pushNotificationProviderTransfer = $pushNotificationSubscriptionTransfer->getProvider();
        $pushNotificationProviderCollectionDeleteCriteriaTransfer = (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->addUuid($pushNotificationProviderTransfer->getUuidOrFail())
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldDeletePushNotificationProviderCollectionForNonTransactionalMode(): void
    {
        $firstPushNotificationProviderTransfer = $this->tester->havePushNotification()->getProvider();
        $secondPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider();

        $pushNotificationProviderCollectionDeleteCriteriaTransfer = (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->addUuid($firstPushNotificationProviderTransfer->getUuidOrFail())
            ->addUuid($secondPushNotificationProviderTransfer->getUuidOrFail())
            ->setIsTransactional(false);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionalIsNotSet(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = $this->tester->havePushNotificationProvider();
        $pushNotificationProviderCollectionDeleteCriteriaTransfer = (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->addUuid($pushNotificationProviderTransfer->getUuidOrFail());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenUuidsIsNotSet(): void
    {
        // Arrange
        $pushNotificationProviderCollectionDeleteCriteriaTransfer = (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->setUuids(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);
    }
}
