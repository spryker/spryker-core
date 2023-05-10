<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServicePointServiceBuilder;
use Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointServiceTransfer;
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
 * @group UpdateServicePointServiceCollectionTest
 * Add your own group annotations below this line
 */
class UpdateServicePointServiceCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_POINT_SERVICE_KEY = 'existing-service-type-key';

    /**
     * @var string
     */
    protected const SERVICE_POINT_SERVICE_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\UuidExistenceServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_ENTITY_NOT_FOUND = 'service_point.validation.service_point_service_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyImmutabilityServicePointServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IMMUTABLILITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IMMUTABLILITY = 'service_point.validation.service_point_service_key_immutability';

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
    public function testShouldUpdateServicePointService(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->haveServicePointService([
            ServicePointServiceTransfer::IS_ACTIVE => false,
        ]);

        $servicePointServiceTransfer->setIsActive(true);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointServiceCollectionResponseTransfer->getErrors());
        $this->assertTrue($servicePointServiceCollectionResponseTransfer->getServicePointServices()
                ->getIterator()
                ->current()
                ->getIsActiveOrFail());
    }

    /**
     * @return void
     */
    public function testShouldUpdateServicePointServiceIsActiveProperty(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->haveServicePointService([
            ServicePointServiceTransfer::IS_ACTIVE => false,
        ]);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer->setIsActive(true))
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointServiceCollectionResponseTransfer->getErrors());
        $this->assertTrue($servicePointServiceCollectionResponseTransfer->getServicePointServices()
            ->getIterator()
            ->current()
            ->getIsActiveOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateKeyImmutability(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->haveServicePointService();

        $servicePointServiceTransfer->setKey(static::SERVICE_POINT_SERVICE_KEY);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IMMUTABLILITY, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateExistenceByUuid(): void
    {
        // Arrange
        $servicePointServiceTransfer = $this->tester->createServicePointServiceTransferWithRelations();

        $servicePointServiceTransfer->setUuid(static::SERVICE_POINT_SERVICE_UUID);

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldUpdateServicePointServicesForNonTransactionalMode(): void
    {
        // Arrange
        $firstServicePointServiceTransfer = $this->tester->haveServicePointService([
            ServicePointServiceTransfer::IS_ACTIVE => false,
        ]);
        $secondServicePointServiceTransfer = $this->tester->haveServicePointService();

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($firstServicePointServiceTransfer->setIsActive(true))
            ->addServicePointService($secondServicePointServiceTransfer->setKey(static::SERVICE_POINT_SERVICE_KEY))
            ->setIsTransactional(false);

        // Act
        $servicePointServiceCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointServiceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointServiceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IMMUTABLILITY, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = (new ServicePointServiceBuilder())->build();
        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServicesAreNotSet(): void
    {
        // Arrange
        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceUuidIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = (new ServicePointServiceBuilder([
            ServicePointServiceTransfer::UUID => null,
        ]))->build();

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceKeyIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = (new ServicePointServiceBuilder([
            ServicePointServiceTransfer::KEY => null,
        ]))->build();

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicePointServiceIsActiveIsNotSet(): void
    {
        // Arrange
        $servicePointServiceTransfer = (new ServicePointServiceBuilder([
            ServicePointServiceTransfer::IS_ACTIVE => null,
        ]))->build();

        $servicePointServiceCollectionRequestTransfer = (new ServicePointServiceCollectionRequestTransfer())
            ->addServicePointService($servicePointServiceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServicePointServiceCollection($servicePointServiceCollectionRequestTransfer);
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

    /**
     * @return \Generated\Shared\Transfer\ServicePointServiceTransfer
     */
    protected function createServicePointServiceTransferWithRelations(): ServicePointServiceTransfer
    {
        $servicePointTransfer = $this->tester->haveServicePoint()->toArray();
        $ServicePointServiceTransfer = $this->tester->haveServicePointService()->toArray();

        return (new ServicePointServiceBuilder())
            ->withServicePoint($servicePointTransfer)
            ->withServicePointService($ServicePointServiceTransfer)
            ->build();
    }
}
