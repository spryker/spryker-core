<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ServicePointCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
 * @group CreateServicePointCollectionTest
 * Add your own group annotations below this line
 */
class CreateServicePointCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\KeyExistenceServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS = 'service_point.validation.service_point_key_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\KeyUniquenessServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_point_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\KeyLengthServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH = 'service_point.validation.service_point_key_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\NameLengthServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH = 'service_point.validation.service_point_name_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\StoreExistenceServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'service_point.validation.store_does_not_exist';

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
    public function testCreatesServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getServicePointQuery()->count());

        /** @var \Generated\Shared\Transfer\ServicePointTransfer $persistedServicePointTransfer */
        $persistedServicePointTransfer = $servicePointCollectionResponseTransfer->getServicePoints()->getIterator()->current();

        $this->assertEquals($servicePointTransfer, $persistedServicePointTransfer);
        $this->assertSame(
            static::STORE_NAME_DE,
            $persistedServicePointTransfer->getStoreRelationOrFail()->getStores()->getIterator()->current()->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testValidatesKeyExistence(): void
    {
        // Arrange
        $existingServicePointTransfer = $this->tester->haveServicePoint();

        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
            [ServicePointTransfer::KEY => $existingServicePointTransfer->getKeyOrFail()],
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServicePointQuery()->count());
    }

    /**
     * @return void
     */
    public function testValidatesKeyUniqueness(): void
    {
        // Arrange
        $firstServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
            [ServicePointTransfer::KEY => 'duplicate'],
        );

        $secondServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
            [ServicePointTransfer::KEY => 'duplicate'],
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($firstServicePointTransfer)
            ->addServicePoint($secondServicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointQuery()->count());
    }

    /**
     * @dataProvider outOfLengthStringDataProvider
     *
     * @param string $key
     *
     * @return void
     */
    public function testValidatesKeyLength(string $key): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
            [ServicePointTransfer::KEY => $key],
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointQuery()->count());
    }

    /**
     * @dataProvider outOfLengthStringDataProvider
     *
     * @param string $name
     *
     * @return void
     */
    public function testValidatesNameLength(string $name): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
            [ServicePointTransfer::NAME => $name],
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointQuery()->count());
    }

    /**
     * @return void
     */
    public function testValidatesStoreExistence(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            'non-existing-store',
            [],
            false,
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getServicePointQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreatesValidServicePointsForNonTransactionalMode(): void
    {
        // Arrange
        $firstServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
        );
        $secondServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_DE,
            [ServicePointTransfer::KEY => ''],
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($firstServicePointTransfer)
            ->addServicePoint($secondServicePointTransfer)
            ->setIsTransactional(false);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
        $this->assertSame(1, $this->tester->getServicePointQuery()->count());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenIsTransactionIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointsAreNotSet(): void
    {
        // Arrange
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointKeyIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())
            ->build()
            ->setKey(null);
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointNameIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())
            ->build()
            ->setName(null);
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointIsActiveIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())
            ->build()
            ->setIsActive(null);
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointStoreRelationIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointStoresAreNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())
            ->build()
            ->setStoreRelation(new StoreRelationTransfer());
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenServicePointStoreNameIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())
            ->build()
            ->setStoreRelation(
                (new StoreRelationTransfer())->addStores(new StoreTransfer()),
            );
        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createServicePointCollection($servicePointCollectionRequestTransfer);
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
