<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use ArrayObject;
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
 * @group UpdateServicePointCollectionTest
 * Add your own group annotations below this line
 */
class UpdateServicePointCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ServicePoint\ServicePointBusinessTester
     */
    protected ServicePointBusinessTester $tester;

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyExistenceServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS = 'service_point.validation.service_point_key_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyUniquenessServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_point_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyLengthServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH = 'service_point.validation.service_point_key_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointNameLengthServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH = 'service_point.validation.service_point_name_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\StoreExistenceServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'service_point.validation.store_does_not_exist';

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
    public function testUpdatesServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $servicePointTransfer = $this->tester->haveServicePoint($servicePointTransfer->toArray());

        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(
            static::STORE_NAME_AT,
            [
                ServicePointTransfer::UUID => $servicePointTransfer->getUuidOrFail(),
                ServicePointTransfer::NAME => 'New name',
            ],
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ServicePointTransfer $persistedServicePointTransfer
         */
        $persistedServicePointTransfer = $servicePointCollectionResponseTransfer->getServicePoints()->getIterator()->current();
        $this->assertSame('New name', $persistedServicePointTransfer->getNameOrFail());
        $this->assertCount(1, $persistedServicePointTransfer->getStoreRelation()->getStores());
        $this->assertSame(
            static::STORE_NAME_AT,
            $persistedServicePointTransfer->getStoreRelationOrFail()->getStores()->getIterator()->current()->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testValidatesKeyExistence(): void
    {
        // Arrange
        $existingServicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::KEY => 'existing-key',
        ]);
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);

        $servicePointTransfer = $this->tester->haveServicePoint($servicePointTransfer->toArray());
        $servicePointTransfer->setKey($existingServicePointTransfer->getKeyOrFail());

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesKeyUniqueness(): void
    {
        // Arrange
        $firstServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $secondServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);

        $firstServicePointTransfer = $this->tester->haveServicePoint($firstServicePointTransfer->toArray());
        $secondServicePointTransfer = $this->tester->haveServicePoint($secondServicePointTransfer->toArray());

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($firstServicePointTransfer->setKey('non-unique'))
            ->addServicePoint($secondServicePointTransfer->setKey('non-unique'))
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
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
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $servicePointTransfer = $this->tester->haveServicePoint($servicePointTransfer->toArray());

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer->setKey($key))
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
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
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $servicePointTransfer = $this->tester->haveServicePoint($servicePointTransfer->toArray());

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer->setName($name))
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testValidatesStoreExistence(): void
    {
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $servicePointTransfer = $this->tester->haveServicePoint($servicePointTransfer->toArray());

        $servicePointTransfer->getStoreRelationOrFail()->setStores(
            new ArrayObject([(new StoreTransfer())->setName('non-existing-store')]),
        );

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($servicePointTransfer)
            ->setIsTransactional(true);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testUpdatesValidServicePointsForNonTransactionalMode(): void
    {
        // Arrange
        $firstServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);
        $secondServicePointTransfer = $this->tester->createServicePointTransferWithStoreRelation(static::STORE_NAME_DE);

        $firstServicePointTransfer = $this->tester->haveServicePoint($firstServicePointTransfer->toArray());
        $secondServicePointTransfer = $this->tester->haveServicePoint($secondServicePointTransfer->toArray());

        $servicePointCollectionRequestTransfer = (new ServicePointCollectionRequestTransfer())
            ->addServicePoint($firstServicePointTransfer->setName('Another name'))
            ->addServicePoint($secondServicePointTransfer->setName(''))
            ->setIsTransactional(false);

        // Act
        $servicePointCollectionResponseTransfer = $this->tester->getFacade()
            ->updateServicePointCollection($servicePointCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $servicePointCollectionResponseTransfer->getErrors());
        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $servicePointCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH, $errorTransfer->getMessageOrFail());
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
    public function testThrowsExceptionWhenServicePointUuidIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())
            ->build()
            ->setUuid(null);
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
