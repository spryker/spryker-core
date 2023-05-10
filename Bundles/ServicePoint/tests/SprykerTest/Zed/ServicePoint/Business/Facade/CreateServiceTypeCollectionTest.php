<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServiceTypeBuilder;
use Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\ServicePoint\ServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePoint
 * @group Business
 * @group Facade
 * @group CreateServiceTypeCollectionTest
 * Add your own group annotations below this line
 */
class CreateServiceTypeCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_TYPE_NAME = 'New Service Type Name';

    /**
     * @var string
     */
    protected const SERVICE_TYPE_KEY = 'existing-service-type-key';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyExistenceServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_EXISTS = 'service_point.validation.service_type_key_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyUniquenessServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_type_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyLengthServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_WRONG_LENGTH = 'service_point.validation.service_type_key_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\NameUniquenessServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_IS_NOT_UNIQUE = 'service_point.validation.service_type_name_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\NameExistenceServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS = 'service_point.validation.service_type_name_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\NameLengthServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH = 'service_point.validation.service_type_name_wrong_length';

    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldCreateServiceType(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $serviceTypeCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServiceTypeQuery()->count());

        /** @var \Generated\Shared\Transfer\ServiceTypeTransfer $persistedServiceTypeTransfer */
        $persistedServiceTypeTransfer = $serviceTypeCollectionResponseTransfer->getServiceTypes()->getIterator()->current();

        $this->assertEquals($serviceTypeTransfer, $persistedServiceTypeTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyExistence(): void
    {
        // Arrange
        $existingServiceTypeTransfer = $this->tester->haveServiceType();

        $serviceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::KEY => $existingServiceTypeTransfer->getKeyOrFail(),
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServiceTypeQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyUniqueness(): void
    {
        // Arrange
        $firstServiceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::KEY => static::SERVICE_TYPE_KEY,
        ]))->build();

        $secondServiceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::KEY => static::SERVICE_TYPE_KEY,
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($firstServiceTypeTransfer)
            ->addServiceType($secondServiceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceTypeQuery()->count());
    }

    /**
     * @dataProvider outOfLengthStringDataProvider
     *
     * @param string $key
     *
     * @return void
     */
    public function testShouldValidateKeyLength(string $key): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::KEY => $key,
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceTypeQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateNameExistence(): void
    {
        // Arrange
        $existingServiceTypeTransfer = $this->tester->haveServiceType();

        $serviceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::NAME => $existingServiceTypeTransfer->getNameOrFail(),
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServiceTypeQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateNameUniqueness(): void
    {
        // Arrange
        $firstServiceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::NAME => static::SERVICE_TYPE_NAME,
        ]))->build();

        $secondServiceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::NAME => static::SERVICE_TYPE_NAME,
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($firstServiceTypeTransfer)
            ->addServiceType($secondServiceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceTypeQuery()->count());
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
        $serviceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::NAME => $name,
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceTypeQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateServiceTypesForNonTransactionalMode(): void
    {
        // Arrange
        $firstServiceTypeTransfer = (new ServiceTypeBuilder())->build();

        $secondServiceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::KEY => '',
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($firstServiceTypeTransfer)
            ->addServiceType($secondServiceTypeTransfer)
            ->setIsTransactional(false);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServiceTypeQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionIsNotSet(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceTypesAreNotSet(): void
    {
        // Arrange
        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceTypeKeyIsNotSet(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())
            ->build()
            ->setKey(null);

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceTypeNameIsNotSet(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())
            ->build()
            ->setName(null);

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceTypeCollection($serviceTypeCollectionRequestTransfer);
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
