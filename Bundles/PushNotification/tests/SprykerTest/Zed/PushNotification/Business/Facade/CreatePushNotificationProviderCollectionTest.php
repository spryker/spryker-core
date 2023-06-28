<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PushNotificationProviderBuilder;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
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
 * @group CreatePushNotificationProviderCollectionTest
 * Add your own group annotations below this line
 */
class CreatePushNotificationProviderCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_PROVIDER_NAME = 'Push Notification Provider Name';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameUniquenessPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE = 'push_notification.validation.push_notification_provider_name_is_not_unique';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameExistencePushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS = 'push_notification.validation.push_notification_provider_name_exists';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameLengthPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH = 'push_notification.validation.push_notification_provider_name_wrong_length';

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
    public function testShouldCreatePushNotificationProvider(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $pushNotificationProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getPushNotificationProviderQuery()->count());

        /** @var \Generated\Shared\Transfer\PushNotificationProviderTransfer $persistedPushNotificationProviderTransfer */
        $persistedPushNotificationProviderTransfer = $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders()->getIterator()->current();

        $this->assertEquals($pushNotificationProviderTransfer, $persistedPushNotificationProviderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateNameExistence(): void
    {
        // Arrange
        $existingPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider();

        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::NAME => $existingPushNotificationProviderTransfer->getNameOrFail(),
        ]))->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateNameUniqueness(): void
    {
        // Arrange
        $firstPushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::NAME => static::PUSH_NOTIFICATION_PROVIDER_NAME,
        ]))->build();

        $secondPushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::NAME => static::PUSH_NOTIFICATION_PROVIDER_NAME,
        ]))->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($firstPushNotificationProviderTransfer)
            ->addPushNotificationProvider($secondPushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @dataProvider outOfLengthStringDataProvider
     *
     * @param string $name
     *
     * @return void
     */
    public function testShouldValidateNameLength(string $name): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::NAME => $name,
        ]))->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreatePushNotificationProvidersForNonTransactionalMode(): void
    {
        // Arrange
        $firstPushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();

        $secondPushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::NAME => '',
        ]))->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($firstPushNotificationProviderTransfer)
            ->addPushNotificationProvider($secondPushNotificationProviderTransfer)
            ->setIsTransactional(false);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getPushNotificationProviderQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionalIsNotSet(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenPushNotificationProvidersAreNotSet(): void
    {
        // Arrange
        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenPushNotificationProviderNameIsNotSet(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder())
            ->build()
            ->setName(null);

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
    }

    /**
     * @return array<list<string>>
     */
    protected function outOfLengthStringDataProvider(): array
    {
        return [
            [''],
            [str_repeat('a', 256)],
        ];
    }
}
