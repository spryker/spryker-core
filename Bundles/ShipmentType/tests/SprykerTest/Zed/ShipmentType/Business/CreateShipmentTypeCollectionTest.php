<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentType\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentTypeBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
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
 * @group CreateShipmentTypeCollectionTest
 * Add your own group annotations below this line
 */
class CreateShipmentTypeCollectionTest extends Unit
{
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
    public function testPersistsShipmentType(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $persistedShipmentTypeTransfer */
        $persistedShipmentTypeTransfer = $shipmentTypeCollectionResponseTransfer->getShipmentTypes()->getIterator()->current();
        $this->assertNotNull($persistedShipmentTypeTransfer->getIdShipmentType());

        $shipmentTypeEntity = $this->tester->findShipmentTypeEntityByIdShipmentType($persistedShipmentTypeTransfer->getIdShipmentTypeOrFail());
        $this->assertNotNull($shipmentTypeEntity);
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $shipmentTypeEntity->getName());
        $this->assertSame($shipmentTypeTransfer->getKeyOrFail(), $shipmentTypeEntity->getKey());
        $this->assertSame($shipmentTypeTransfer->getIsActiveOrFail(), $shipmentTypeEntity->getIsActive());

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
    public function testReturnsValidationErrorWhenShipmentTypeKeyAlreadyExists(): void
    {
        // Arrange
        $existingShipmentTypeTransfer = $this->tester->haveShipmentType();
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer, [
            ShipmentTypeTransfer::KEY => $existingShipmentTypeTransfer->getKeyOrFail(),
        ]);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getShipmentTypeEntitiesCount());

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
        $shipmentTypeTransfer1 = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer);
        $shipmentTypeTransfer2 = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer, [
            ShipmentTypeTransfer::KEY => $shipmentTypeTransfer1->getKeyOrFail(),
        ]);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer1)
            ->addShipmentType($shipmentTypeTransfer2)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeEntitiesCount());

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
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer, [
            ShipmentTypeTransfer::KEY => $key,
        ]);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeEntitiesCount());

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
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer, [
            ShipmentTypeTransfer::NAME => $name,
        ]);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeEntitiesCount());

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
        $storeTransfer = (new StoreBuilder([StoreTransfer::NAME => 'XX']))->build();
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($shipmentTypeTransfer)
            ->setIsTransactional(true);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getShipmentTypeEntitiesCount());

        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    protected function testPersistsValidEntitiesInNonTransactionalRequest(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $validShipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer);
        $invalidShipmentTypeTransfer = $this->tester->createShipmentTypeTransferWithStoreRelation($storeTransfer, [
            ShipmentTypeTransfer::KEY => '',
        ]);

        $shipmentTypeCollectionRequestTransfer = (new ShipmentTypeCollectionRequestTransfer())
            ->addShipmentType($invalidShipmentTypeTransfer)
            ->addShipmentType($validShipmentTypeTransfer)
            ->setIsTransactional(false);

        // Act
        $shipmentTypeCollectionResponseTransfer = $this->tester->getFacade()
            ->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getShipmentTypeEntitiesCount());

        $this->assertCount(2, $shipmentTypeCollectionResponseTransfer->getShipmentTypes());
        $this->assertCount(1, $shipmentTypeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $shipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH, $errorTransfer->getMessage());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $persistedServicePointTransfer */
        $persistedServicePointTransfer = $shipmentTypeCollectionResponseTransfer->getShipmentTypes()->offsetGet('1');
        $this->assertNotNull($persistedServicePointTransfer->getIdShipmentType());
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
        $this->tester->getFacade()->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
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
        $this->tester->getFacade()->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
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
        $this->tester->getFacade()->createShipmentTypeCollection($shipmentTypeCollectionRequestTransfer);
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
