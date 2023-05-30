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
 * @group UpdateServiceTypeCollectionTest
 * Add your own group annotations below this line
 */
class UpdateServiceTypeCollectionTest extends Unit
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
     * @var string
     */
    protected const SERVICE_TYPE_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

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
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyImmutabilityServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_KEY_IMMUTABILITY
     *
     * @var string
     */
    protected const SERVICE_POINT_VALIDATION_SERVICE_TYPE_KEY_IMMUTABILITY = 'service_point.validation.service_type_key_immutability';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\UuidExistenceServiceTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND = 'service_point.validation.service_type_entity_not_found';

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
    public function testShouldUpdateServiceType(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())->build();
        $serviceTypeTransfer = $this->tester->haveServiceType($serviceTypeTransfer->toArray());

        $serviceTypeTransfer->setName(static::SERVICE_TYPE_NAME);

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $serviceTypeCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::SERVICE_TYPE_NAME,
            $serviceTypeCollectionResponseTransfer->getServiceTypes()
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
        $existingServiceTypeTransfer = $this->tester->haveServiceType([
            ServiceTypeTransfer::NAME => static::SERVICE_TYPE_NAME,
        ]);

        $serviceTypeTransfer = $this->tester->haveServiceType();
        $serviceTypeTransfer->setName($existingServiceTypeTransfer->getNameOrFail());

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateNameUniqueness(): void
    {
        // Arrange
        $firstServiceTypeTransfer = (new ServiceTypeBuilder())->build();
        $secondServiceTypeTransfer = (new ServiceTypeBuilder())->build();

        $firstServiceTypeTransfer = $this->tester->haveServiceType($firstServiceTypeTransfer->toArray());
        $secondServiceTypeTransfer = $this->tester->haveServiceType($secondServiceTypeTransfer->toArray());

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($firstServiceTypeTransfer->setName(static::SERVICE_TYPE_NAME))
            ->addServiceType($secondServiceTypeTransfer->setName(static::SERVICE_TYPE_NAME))
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
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
        $serviceTypeTransfer = (new ServiceTypeBuilder())->build();
        $serviceTypeTransfer = $this->tester->haveServiceType($serviceTypeTransfer->toArray());

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer->setName($name))
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyImmutable(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->tester->haveServiceType();

        $serviceTypeTransfer->setKey(static::SERVICE_TYPE_KEY);

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::SERVICE_POINT_VALIDATION_SERVICE_TYPE_KEY_IMMUTABILITY, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateExistenceByUuid(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::UUID => static::SERVICE_TYPE_UUID,
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldUpdateServiceTypesForNonTransactionalMode(): void
    {
        // Arrange
        $firstServiceTypeTransfer = (new ServiceTypeBuilder())->build();
        $secondServiceTypeTransfer = (new ServiceTypeBuilder())->build();

        $firstServiceTypeTransfer = $this->tester->haveServiceType($firstServiceTypeTransfer->toArray());
        $secondServiceTypeTransfer = $this->tester->haveServiceType($secondServiceTypeTransfer->toArray());

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($firstServiceTypeTransfer->setName(static::SERVICE_TYPE_NAME))
            ->addServiceType($secondServiceTypeTransfer->setName(''))
            ->setIsTransactional(false);

        // Act
        $serviceTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceTypeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
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
        $this->tester->getFacade()->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);
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
        $this->tester->getFacade()->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceTypeUuidIsNotSet(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder([
            ServiceTypeTransfer::UUID => null,
        ]))->build();

        $serviceTypeCollectionRequestTransfer = (new ServiceTypeCollectionRequestTransfer())
            ->addServiceType($serviceTypeTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);
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
        $this->tester->getFacade()->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);
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
        $this->tester->getFacade()->updateServiceTypeCollection($serviceTypeCollectionRequestTransfer);
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
