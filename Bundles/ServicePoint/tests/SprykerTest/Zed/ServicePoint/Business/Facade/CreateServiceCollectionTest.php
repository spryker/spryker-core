<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\DataBuilder\ServiceTypeBuilder;
use Generated\Shared\Transfer\ServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
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
 * @group CreateServiceCollectionTest
 * Add your own group annotations below this line
 */
class CreateServiceCollectionTest extends Unit
{
   /**
    * @var string
    */
    protected const SERVICE_KEY = 'service-key';

    /**
     * @var string
     */
    protected const SERVICE_TYPE_KEY = 'service-type-key';

    /**
     * @var string
     */
    protected const UNKNOWN_SERVICE_POINT_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServicePointUuidExistenceServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND = 'service_point.validation.service_point_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceTypeUuidExistenceServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND = 'service_point.validation.service_type_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceTypeExistenceServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_ALREADY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_ALREADY_EXISTS = 'service_point.validation.service_type_relation_already_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceTypeUniquenessServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_IS_NOT_UNIQUE = 'service_point.validation.service_type_relation_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\KeyUniquenessServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\KeyExistenceServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_KEY_EXISTS = 'service_point.validation.service_key_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\KeyLengthServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_KEY_WRONG_LENGTH = 'service_point.validation.service_key_wrong_length';

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
    public function testShouldCreateService(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations();

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $serviceCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServiceQuery()->count());

        /** @var \Generated\Shared\Transfer\ServiceTransfer $persistedServiceTransfer */
        $persistedServiceTransfer = $serviceCollectionResponseTransfer->getServices()->getIterator()->current();

        $this->assertEquals($serviceTransfer, $persistedServiceTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyExistence(): void
    {
        // Arrange
        $existingServiceTransfer = $this->tester->haveService();
        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setKey($existingServiceTransfer->getKeyOrFail());

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyUniqueness(): void
    {
        // Arrange
        $firstServiceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setKey(static::SERVICE_KEY);

        $secondServiceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setKey(static::SERVICE_KEY);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($firstServiceTransfer)
            ->addService($secondServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceQuery()->count());
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
        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setKey($key);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateServiceTypeExistence(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())->build()
            ->setUuid(static::UNKNOWN_SERVICE_POINT_UUID);

        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setServiceType($serviceTypeTransfer);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateServicePointExistence(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build()
            ->setUuid(static::UNKNOWN_SERVICE_POINT_UUID);

        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setServicePoint($servicePointTransfer);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateServiceTypeRelationUniqueness(): void
    {
        $servicePointTransfer = $this->tester->haveServicePoint();
        $serviceTypeTransfer = $this->tester->haveServiceType();

        // Arrange
        $firstServiceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setUuid(null)
            ->setServicePoint($servicePointTransfer)
            ->setServiceType($serviceTypeTransfer);

        $secondServiceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setUuid(null)
            ->setServicePoint($servicePointTransfer)
            ->setServiceType($serviceTypeTransfer);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($firstServiceTransfer)
            ->addService($secondServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateServiceTypeRelationExistence(): void
    {
        // Arrange
        $existingServiceTransfer = $this->tester->haveService();
        $servicePointTransfer = (new ServicePointTransfer())->setUuid($existingServiceTransfer->getServicePointOrFail()->getUuidOrFail());
        $serviceTypeTransfer = (new ServiceTypeTransfer())->setUuid($existingServiceTransfer->getServiceTypeOrFail()->getUuidOrFail());

        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setUuid(null)
            ->setServicePoint($servicePointTransfer)
            ->setServiceType($serviceTypeTransfer);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_ALREADY_EXISTS, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldCreateServiceForNonTransactionalMode(): void
    {
        // Arrange
        $firstServiceTransfer = $this->tester->createServiceTransferWithRelations();

        $secondServiceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setKey('');

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($firstServiceTransfer)
            ->addService($secondServiceTransfer)
            ->setIsTransactional(false);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionPropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations();

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicesPropertyIsNotSet(): void
    {
        // Arrange
        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceIsActivePropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setIsActive(null);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceKeyPropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setKey(null);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointPropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setServicePoint(null);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointUuidPropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations();

        $serviceTransfer->getServicePointOrFail()->setUuid(null);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceTypePropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations()
            ->setServiceType(null);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceTypeUuidPropertyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations();

        $serviceTransfer->getServiceTypeOrFail()->setUuid(null);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServiceCollection($serviceCollectionRequestTransfer);
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
