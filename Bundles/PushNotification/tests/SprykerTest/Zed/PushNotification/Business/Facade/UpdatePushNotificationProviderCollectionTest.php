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
 * @group UpdatePushNotificationProviderCollectionTest
 * Add your own group annotations below this line
 */
class UpdatePushNotificationProviderCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_PROVIDER_NAME = 'Push Notification Provider Name';

    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_PROVIDER_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameUniquenessPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE = 'push_notification.validation.push_notification_provider_name_is_not_unique';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameExistencePushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS
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
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\UuidExistencePushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND = 'push_notification.validation.error.push_notification_provider_not_found';

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
    public function testShouldUpdatePushNotificationProvider(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();
        $pushNotificationProviderTransfer = $this->tester->havePushNotificationProvider($pushNotificationProviderTransfer->toArray());

        $pushNotificationProviderTransfer->setName(static::PUSH_NOTIFICATION_PROVIDER_NAME);

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $pushNotificationProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::PUSH_NOTIFICATION_PROVIDER_NAME,
            $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders()
                ->getIterator()
                ->current()
                ->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateNameExistence(): void
    {
        // Arrange
        $existingPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider([
            PushNotificationProviderTransfer::NAME => static::PUSH_NOTIFICATION_PROVIDER_NAME,
        ]);

        $pushNotificationProviderTransfer = $this->tester->havePushNotificationProvider();
        $pushNotificationProviderTransfer->setName($existingPushNotificationProviderTransfer->getNameOrFail());

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateNameUniqueness(): void
    {
        // Arrange
        $firstPushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();
        $secondPushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();

        $firstPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider($firstPushNotificationProviderTransfer->toArray());
        $secondPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider($secondPushNotificationProviderTransfer->toArray());

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($firstPushNotificationProviderTransfer->setName(static::PUSH_NOTIFICATION_PROVIDER_NAME))
            ->addPushNotificationProvider($secondPushNotificationProviderTransfer->setName(static::PUSH_NOTIFICATION_PROVIDER_NAME))
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
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
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();
        $pushNotificationProviderTransfer = $this->tester->havePushNotificationProvider($pushNotificationProviderTransfer->toArray());

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer->setName($name))
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateExistenceByUuid(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::UUID => static::PUSH_NOTIFICATION_PROVIDER_UUID,
        ]))->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldUpdatePushNotificationProvidersForNonTransactionalMode(): void
    {
        // Arrange
        $firstPushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();
        $secondPushNotificationProviderTransfer = (new PushNotificationProviderBuilder())->build();

        $firstPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider($firstPushNotificationProviderTransfer->toArray());
        $secondPushNotificationProviderTransfer = $this->tester->havePushNotificationProvider($secondPushNotificationProviderTransfer->toArray());

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($firstPushNotificationProviderTransfer->setName(static::PUSH_NOTIFICATION_PROVIDER_NAME))
            ->addPushNotificationProvider($secondPushNotificationProviderTransfer->setName(''))
            ->setIsTransactional(false);

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $pushNotificationProviderCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $pushNotificationProviderCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
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
        $this->tester->getFacade()->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
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
        $this->tester->getFacade()->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenPushNotificationProviderUuidIsNotSet(): void
    {
        // Arrange
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder([
            PushNotificationProviderTransfer::UUID => null,
        ]))->build();

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
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
        $this->tester->getFacade()->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
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
