<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\DataBuilder\ServiceTypeBuilder;
use Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer;
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
 * @group CreateServicePointServiceCollectionTest
 * Add your own group annotations below this line
 */
class CreateServicePointServiceCollectionTest extends Unit
{
   /**
    * @var string
    */
    protected const SERVICE_POINT_SERVICE_KEY = 'service-point-service-key';

    /**
     * @var string
     */
    protected const SERVICE_POINT_SERVICE_SERVICE_TYPE_KEY = 'service-point-service-service-type-key';

    /**
     * @var string
     */
    protected const UNKNOWN_SERVICE_POINT_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointUuidExistenceServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND = 'service_point.validation.service_point_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServiceTypeUuidExistenceServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND = 'service_point.validation.service_type_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceTypeExistenceServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_TYPE_RELATION_ALREADY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_TYPE_RELATION_ALREADY_EXISTS = 'service_point.validation.service_point_service_type_relation_already_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceTypeUniquenessServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_TYPE_RELATION_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_TYPE_RELATION_IS_NOT_UNIQUE = 'service_point.validation.service_point_service_type_relation_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyUniquenessServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_point_service_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyExistenceServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_EXISTS = 'service_point.validation.service_point_service_key_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyLengthServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_WRONG_LENGTH = 'service_point.validation.service_point_service_key_wrong_length';

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
    public function testShouldCreateServicePointService(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations();

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointServiceCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServicePointServiceQuery()->count());

        /** @var \Generated\Shared\Transfer\ServicePointServiceTransfer $persistedServicePointServiceTransfer */
        $persistedServicePointServiceTransfer = $servicePointServiceCollectionResponseTransfer->getServicePointServices()->getIterator()->current();

        $this->assertEquals($servicePointServiceTransfer, $persistedServicePointServiceTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyExistence(): void
    {
        // Arrange
        $existingServicePointServiceTransfer = $this->tester->haveServicePointService();
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setKey($existingServicePointServiceTransfer->getKeyOrFail());

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServicePointServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyUniqueness(): void
    {
        // Arrange
        $firstServicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setKey(static::SERVICE_POINT_SERVICE_KEY);

        $secondServicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setKey(static::SERVICE_POINT_SERVICE_KEY);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($firstServicePointServiceTransfer)
            ->addServicePointService($secondServicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointServiceQuery()->count());
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
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setKey($key);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateServiceTypeExistence(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeBuilder())->build()
            ->setUuid(static::UNKNOWN_SERVICE_POINT_UUID);

        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setServiceType($serviceTypeTransfer);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateServicePointExistence(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build()
            ->setUuid(static::UNKNOWN_SERVICE_POINT_UUID);

        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setServicePoint($servicePointTransfer);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldValidateServicePointServiceTypeRelationUniqueness(): void
    {
        $servicePointTransfer = $this->tester->haveServicePoint();
        $serviceTypeTransfer = $this->tester->haveServiceType();

        // Arrange
        $firstServicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setUuid(null)
            ->setServicePoint($servicePointTransfer)
            ->setServiceType($serviceTypeTransfer);

        $secondServicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setUuid(null)
            ->setServicePoint($servicePointTransfer)
            ->setServiceType($serviceTypeTransfer);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($firstServicePointServiceTransfer)
            ->addServicePointService($secondServicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_TYPE_RELATION_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateServicePointServiceTypeRelationExistence(): void
    {
        // Arrange
        $existingServicePointServiceTransfer = $this->tester->haveServicePointService();
        $servicePointTransfer = (new ServicePointTransfer())->setUuid($existingServicePointServiceTransfer->getServicePointOrFail()->getUuidOrFail());
        $serviceTypeTransfer = (new ServiceTypeTransfer())->setUuid($existingServicePointServiceTransfer->getServiceTypeOrFail()->getUuidOrFail());

        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setUuid(null)
            ->setServicePoint($servicePointTransfer)
            ->setServiceType($serviceTypeTransfer);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_TYPE_RELATION_ALREADY_EXISTS, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldCreateServicePointServiceForNonTransactionalMode(): void
    {
        // Arrange
        $firstServicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations();

        $secondServicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setKey('');

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($firstServicePointServiceTransfer)
            ->addServicePointService($secondServicePointServiceTransfer)
            ->setIsTransactional(false);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServicePointServiceQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionPropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations();

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServicesPropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceIsActivePropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setIsActive(null);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceKeyPropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setKey(null);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceServicePointPropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setServicePoint(null);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceServicePointUuidPropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations();

        $servicePointServiceTransfer->getServicePointOrFail()->setUuid(null);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceServiceTypePropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations()
            ->setServiceType(null);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceServiceTypeUuidPropertyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations();

        $servicePointServiceTransfer->getServiceTypeOrFail()->setUuid(null);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
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
