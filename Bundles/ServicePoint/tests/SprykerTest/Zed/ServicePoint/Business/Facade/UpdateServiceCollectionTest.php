<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServiceBuilder;
use Generated\Shared\Transfer\ServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
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
 * @group UpdateServiceCollectionTest
 * Add your own group annotations below this line
 */
class UpdateServiceCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_KEY = 'existing-service-key';

    /**
     * @var string
     */
    protected const SERVICE_UUID = 'aaaaaaaa-bbbbb-cccc-dddd-eeeeeeeeeeee';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\UuidExistenceServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_ENTITY_NOT_FOUND = 'service_point.validation.service_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\KeyImmutabilityServiceValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IMMUTABILITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IMMUTABILITY = 'service_point.validation.service_key_immutability';

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
    public function testShouldUpdateService(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
        ]);

        $serviceTransfer->setIsActive(true);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $serviceCollectionResponseTransfer->getErrors());
        $this->assertTrue($serviceCollectionResponseTransfer->getServices()
                ->getIterator()
                ->current()
                ->getIsActiveOrFail());
    }

    /**
     * @return void
     */
    public function testShouldUpdateServiceIsActiveProperty(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
        ]);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer->setIsActive(true))
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $serviceCollectionResponseTransfer->getErrors());
        $this->assertTrue($serviceCollectionResponseTransfer->getServices()
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
        $serviceTransfer = $this->tester->haveService();

        $serviceTransfer->setKey(static::SERVICE_KEY);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IMMUTABILITY, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldValidateExistenceByUuid(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->createServiceTransferWithRelations();

        $serviceTransfer->setUuid(static::SERVICE_UUID);

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Act
        $serviceCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->updateServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldUpdateServicesForNonTransactionalMode(): void
    {
        // Arrange
        $firstServiceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
        ]);
        $secondServiceTransfer = $this->tester->haveService();

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($firstServiceTransfer->setIsActive(true))
            ->addService($secondServiceTransfer->setKey(static::SERVICE_KEY))
            ->setIsTransactional(false);

        // Act
        $serviceCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServiceCollection($serviceCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $serviceCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $serviceCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_KEY_IMMUTABILITY, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenIsTransactionIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = (new ServiceBuilder())->build();
        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServicesAreNotSet(): void
    {
        // Arrange
        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceUuidIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = (new ServiceBuilder([
            ServiceTransfer::UUID => null,
        ]))->build();

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceKeyIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = (new ServiceBuilder([
            ServiceTransfer::KEY => null,
        ]))->build();

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServiceCollection($serviceCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenServiceIsActiveIsNotSet(): void
    {
        // Arrange
        $serviceTransfer = (new ServiceBuilder([
            ServiceTransfer::IS_ACTIVE => null,
        ]))->build();

        $serviceCollectionRequestTransfer = (new ServiceCollectionRequestTransfer())
            ->addService($serviceTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateServiceCollection($serviceCollectionRequestTransfer);
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
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    protected function createServiceTransferWithRelations(): ServiceTransfer
    {
        $servicePointTransfer = $this->tester->haveServicePoint()->toArray();
        $ServiceTransfer = $this->tester->haveService()->toArray();

        return (new ServiceBuilder())
            ->withServicePoint($servicePointTransfer)
            ->withService($ServiceTransfer)
            ->build();
    }
}
