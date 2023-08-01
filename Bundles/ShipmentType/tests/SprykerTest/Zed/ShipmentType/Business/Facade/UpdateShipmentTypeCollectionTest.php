<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentType\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentTypeBuilder;
use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentType
 * @group Business
 * @group Facade
 * @group UpdateShipmentTypeCollectionTest
 * Add your own group annotations below this line
 */
class UpdateShipmentTypeCollectionTest extends Unit
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
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeExistsShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND = 'shipment_type.validation.shipment_type_entity_not_found';

    /**
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeKeyExistsShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS = 'shipment_type.validation.shipment_type_key_exists';

    /**
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeKeyLengthShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH = 'shipment_type.validation.shipment_type_key_invalid_length';

    /**
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeKeyUniqueShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE = 'shipment_type.validation.shipment_type_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeNameLengthShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NAME_INVALID_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NAME_INVALID_LENGTH = 'shipment_type.validation.shipment_type_name_invalid_length';

    /**
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\StoreExistsShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'shipment_type.validation.store_does_not_exist';

    /**
     * @var \SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester
     */
    protected ShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureShipmentTypeDatabaseIsEmpty();
    }

    /**
     * @return void
     */
    public function testUpdatesShipmentType(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $existingShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeTransfer = (new ShipmentTypeBuilder())->build();
        $shipmentTypeTransfer
            ->setUuid($existingShipmentTypeTransfer->getUuidOrFail())
            ->setStoreRelation($existingShipmentTypeTransfer->getStoreRelationOrFail());

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());

        $shipmentTypeEntity = $this->tester->findShipmentTypeEntityByIdShipmentType($existingShipmentTypeTransfer->getIdShipmentTypeOrFail());
        $this->assertNotNull($shipmentTypeEntity);
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $shipmentTypeEntity->getName());
        $this->assertSame($shipmentTypeTransfer->getKeyOrFail(), $shipmentTypeEntity->getKey());
        $this->assertSame($shipmentTypeTransfer->getIsActiveOrFail(), $shipmentTypeEntity->getIsActive());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $persistedShipmentTypeTransfer */
        $persistedShipmentTypeTransfer = $shipmentTypeCollectionResponseTransfer->getShipmentTypes()->getIterator()->current();
        $this->assertNotNull($persistedShipmentTypeTransfer->getStoreRelation());
        $this->assertCount(1, $persistedShipmentTypeTransfer->getStoreRelation()->getStores());
        $this->assertSame($shipmentTypeEntity->getIdShipmentType(), $persistedShipmentTypeTransfer->getStoreRelation()->getIdEntity());

        /** @var \Generated\Shared\Transfer\StoreTransfer $retrievedStoreTransfer */
        $retrievedStoreTransfer = $persistedShipmentTypeTransfer->getStoreRelation()->getStores()->getIterator()->current();
        $this->assertSame($storeTransfer->getIdStoreOrFail(), $retrievedStoreTransfer->getIdStore());
        $this->assertSame(1, $this->tester->getShipmentTypeStoreRelationCountByIdShipmentType($shipmentTypeEntity->getIdShipmentType()));
    }

    /**
     * @return void
     */
    public function testAddsShipmentTypeStoreRelation(): void
    {
        // Arrange
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $existingShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeAtTransfer),
        ]);

        $shipmentTypeTransfer = (new ShipmentTypeBuilder())->build();
        $shipmentTypeTransfer
            ->setUuid($existingShipmentTypeTransfer->getUuidOrFail())
            ->setStoreRelation($existingShipmentTypeTransfer->getStoreRelationOrFail()->addStores($storeDeTransfer));

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $persistedShipmentTypeTransfer */
        $persistedShipmentTypeTransfer = $shipmentTypeCollectionResponseTransfer->getShipmentTypes()->getIterator()->current();
        $this->assertNotNull($persistedShipmentTypeTransfer->getStoreRelation());
        $this->assertCount(2, $persistedShipmentTypeTransfer->getStoreRelation()->getStores());
        $this->assertSame($persistedShipmentTypeTransfer->getIdShipmentTypeOrFail(), $persistedShipmentTypeTransfer->getStoreRelation()->getIdEntity());

        $storeTransfers = $persistedShipmentTypeTransfer->getStoreRelation()->getStores();
        $this->assertSame(static::STORE_NAME_AT, $storeTransfers->offsetGet(0)->getName());
        $this->assertSame(static::STORE_NAME_DE, $storeTransfers->offsetGet(1)->getName());

        $this->assertSame(2, $this->tester->getShipmentTypeStoreRelationCountByIdShipmentType($persistedShipmentTypeTransfer->getIdShipmentType()));
    }

    /**
     * @return void
     */
    public function testRemovesShipmentTypeStoreRelation(): void
    {
        // Arrange
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $existingShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addStores($storeAtTransfer)
                ->addStores($storeDeTransfer),
        ]);

        $shipmentTypeTransfer = (new ShipmentTypeBuilder())->build();
        $shipmentTypeTransfer
            ->setUuid($existingShipmentTypeTransfer->getUuidOrFail())
            ->setStoreRelation((new StoreRelationTransfer())->addStores($storeAtTransfer));

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $persistedShipmentTypeTransfer */
        $persistedShipmentTypeTransfer = $shipmentTypeCollectionResponseTransfer->getShipmentTypes()->getIterator()->current();
        $this->assertNotNull($persistedShipmentTypeTransfer->getStoreRelation());
        $this->assertCount(1, $persistedShipmentTypeTransfer->getStoreRelation()->getStores());
        $this->assertSame($persistedShipmentTypeTransfer->getIdShipmentTypeOrFail(), $persistedShipmentTypeTransfer->getStoreRelation()->getIdEntity());

        $storeTransfers = $persistedShipmentTypeTransfer->getStoreRelation()->getStores();
        $this->assertSame(static::STORE_NAME_AT, $storeTransfers->offsetGet(0)->getName());

        $this->assertSame(1, $this->tester->getShipmentTypeStoreRelationCountByIdShipmentType($persistedShipmentTypeTransfer->getIdShipmentType()));
    }

    /**
     * @return void
     */
    public function testUpdatesShipmentTypeStoreRelation(): void
    {
        // Arrange
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $existingShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeAtTransfer),
        ]);

        $shipmentTypeTransfer = (new ShipmentTypeBuilder())->build();
        $shipmentTypeTransfer
            ->setUuid($existingShipmentTypeTransfer->getUuidOrFail())
            ->setStoreRelation((new StoreRelationTransfer())->addStores($storeDeTransfer));

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $persistedShipmentTypeTransfer */
        $persistedShipmentTypeTransfer = $shipmentTypeCollectionResponseTransfer->getShipmentTypes()->getIterator()->current();
        $this->assertNotNull($persistedShipmentTypeTransfer->getStoreRelation());
        $this->assertCount(1, $persistedShipmentTypeTransfer->getStoreRelation()->getStores());
        $this->assertSame($persistedShipmentTypeTransfer->getIdShipmentTypeOrFail(), $persistedShipmentTypeTransfer->getStoreRelation()->getIdEntity());

        $storeTransfers = $persistedShipmentTypeTransfer->getStoreRelation()->getStores();
        $this->assertSame(static::STORE_NAME_DE, $storeTransfers->offsetGet(0)->getName());

        $this->assertSame(1, $this->tester->getShipmentTypeStoreRelationCountByIdShipmentType($persistedShipmentTypeTransfer->getIdShipmentType()));
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorIfShipmentTypeDoesNotExist(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer);
        $shipmentTypeTransfer->setUuid('non-existing-uuid');

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenShipmentTypeKeyAlreadyExists(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $existingShipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $existingShipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->fromArray($existingShipmentTypeTransfer2->toArray())
            ->setKey($existingShipmentTypeTransfer1->getKeyOrFail());

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenShipmentTypeKeyNotUnique(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer1->setKey('not-unique-key'))
            ->addShipmentType($shipmentTypeTransfer2->setKey('not-unique-key'))
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE, $errorTransfer->getMessage());
    }

    /**
     * @dataProvider invalidLengthStringDataProvider
     *
     * @param string $key
     *
     * @return void
     */
    public function testReturnsValidationErrorWhenShipmentTypeKeyLengthIsInvalid(string $key): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ])->setKey($key);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH, $errorTransfer->getMessage());
    }

    /**
     * @dataProvider invalidLengthStringDataProvider
     *
     * @param string $name
     *
     * @return void
     */
    public function testReturnsValidationErrorWhenShipmentTypeNameLengthIsInvalid(string $name): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ])->setName($name);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NAME_INVALID_LENGTH, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenInvalidStoreNameProvided(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer->getStoreRelation()->getStores()->getIterator()->current()->setName('XX');
        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST, $errorTransfer->getMessage());
    }

    /**
     * @dataProvider throwExceptionWhenShipmentTypeCollectionRequestTransferRequiredPropertyIsMissingDataProvider
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testThrowExceptionWhenShipmentTypeCollectionRequestTransferRequiredPropertyIsMissing(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): void {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredShipmentTypeTransferPropertyIsMissingDataProvider
     *
     * @param array<string, array<string, null>> $seedData
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredShipmentTypeTransferPropertyIsMissing(array $seedData): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer, $seedData);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }

    /**
     * @dataProvider trowsExceptionWhenRequireStoreRelationTransferPropertyIsMissingDataProvider
     *
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $storeRelationTransfer
     *
     * @return void
     */
    public function testTrowsExceptionWhenRequireStoreRelationTransferPropertyIsMissing(?StoreRelationTransfer $storeRelationTransfer): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeBuilder())->build();
        $shipmentTypeTransfer->setStoreRelation($storeRelationTransfer);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
    }

    /**
     * @return array<string, list<string>>
     */
    protected function invalidLengthStringDataProvider(): array
    {
        return [
            'String is too short' => [''],
            'String is too long' => [str_repeat('x', 256)],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer>>
     */
    protected function throwExceptionWhenShipmentTypeCollectionRequestTransferRequiredPropertyIsMissingDataProvider(): array
    {
        return [
            'Shipment types are not set' => [
                (new ShipmentTypeCollectionRequestTransfer())
                    ->setShipmentTypes(new ArrayObject())
                    ->setIsTransactional(true),
            ],
            'IsTrans not set' => [
                (new ShipmentTypeCollectionRequestTransfer())
                    ->addShipmentType(new ShipmentTypeTransfer())
                    ->setIsTransactional(null),
            ],
        ];
    }

    /**
     * @return array<string, array<string, null>>
     */
    protected function throwsExceptionWhenRequiredShipmentTypeTransferPropertyIsMissingDataProvider(): array
    {
        return [
            'Shipment type UUID is not set' => [[ShipmentTypeTransfer::UUID => null]],
            'Shipment type key is not set' => [[ShipmentTypeTransfer::KEY => null]],
            'Shipment type name is not set' => [[ShipmentTypeTransfer::NAME => null]],
            'Shipment type isActive is not set' => [[ShipmentTypeTransfer::IS_ACTIVE => null]],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\StoreRelationTransfer|null>>
     */
    protected function trowsExceptionWhenRequireStoreRelationTransferPropertyIsMissingDataProvider(): array
    {
        return [
            'Store relation transfer is not provided' => [null],
            'Store name is not set' => [
                (new StoreRelationTransfer())->addStores(
                    (new StoreTransfer())->setName(null),
                ),
            ],
        ];
    }
}
