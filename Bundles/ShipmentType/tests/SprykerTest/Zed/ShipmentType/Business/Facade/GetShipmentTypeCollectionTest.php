<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentType
 * @group Business
 * @group Facade
 * @group GetShipmentTypeCollectionTest
 * Add your own group annotations below this line
 */
class GetShipmentTypeCollectionTest extends Unit
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
    public function testReturnsCorrectShipmentTypeByUuid(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addUuid($shipmentTypeTransfer->getUuidOrFail());
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentTypeByIdShipmentType(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addIdShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail());
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentTypeByKey(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addKey($shipmentTypeTransfer->getKeyOrFail());
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentTypeByName(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addName($shipmentTypeTransfer->getNameOrFail());
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentTypeByIsActiveStatus(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            ShipmentTypeTransfer::IS_ACTIVE => false,
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setIsActive(false);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentTypeByStoreName(): void
    {
        // Arrange
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeDeTransfer),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeAtTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addStoreName($storeAtTransfer->getNameOrFail());
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentTypeByStoreNamesWithoutDuplicates(): void
    {
        // Arrange
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addStores($storeDeTransfer)
                ->addStores($storeAtTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->addStoreName($storeDeTransfer->getNameOrFail())
            ->addStoreName($storeAtTransfer->getNameOrFail());
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertSameShipmentTypeTransfer(
            $shipmentTypeTransfer,
            $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypeWithStoreRelations(): void
    {
        // Arrange
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addStores($storeAtTransfer)
                ->addStores($storeDeTransfer),
        ]);

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setWithStoreRelations(true);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeTransfer $retrievedShipmentTypeTransfer */
        $retrievedShipmentTypeTransfer = $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator()->current();
        $this->assertSameShipmentTypeTransfer($shipmentTypeTransfer, $retrievedShipmentTypeTransfer);

        $retrievedStoreRelationTransfer = $retrievedShipmentTypeTransfer->getStoreRelation();
        $this->assertNotNull($retrievedStoreRelationTransfer);
        $this->assertSame($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $retrievedStoreRelationTransfer->getIdEntity());
        $this->assertCount(2, $retrievedStoreRelationTransfer->getStores());

        $retrievedStoreDeTransfer = $this->findStoreTransferInStoreRelationTransfer($retrievedStoreRelationTransfer, static::STORE_NAME_DE);
        $this->assertNotNull($retrievedStoreDeTransfer);
        $this->assertSame($storeDeTransfer->getIdStoreOrFail(), $retrievedStoreDeTransfer->getIdStore());

        $retrievedStoreAtTransfer = $this->findStoreTransferInStoreRelationTransfer($retrievedStoreRelationTransfer, static::STORE_NAME_AT);
        $this->assertNotNull($retrievedStoreAtTransfer);
        $this->assertSame($storeAtTransfer->getIdStoreOrFail(), $retrievedStoreAtTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveShipmentType();
        $this->tester->haveShipmentType();
        $this->tester->haveShipmentType();
        $this->tester->haveShipmentType();

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(1)
            ->setLimit(2);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertNotNull($shipmentTypeCollectionTransfer->getPagination());
        $this->assertSame(4, $shipmentTypeCollectionTransfer->getPaginationOrFail()->getNbResults());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveShipmentType();
        $this->tester->haveShipmentType();
        $this->tester->haveShipmentType();
        $this->tester->haveShipmentType();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $this->assertNotNull($shipmentTypeCollectionTransfer->getPagination());
        $this->assertSame(4, $shipmentTypeCollectionTransfer->getPaginationOrFail()->getNbResults());

        $paginationTransfer = $shipmentTypeCollectionTransfer->getPaginationOrFail();

        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(4, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(3, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(4, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(2, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(2, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesSortedByKeyFieldDesc(): void
    {
        // Arrange
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => 'abc']);
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => 'def']);
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => 'ghi']);

        $sortTransfer = (new SortTransfer())
            ->setField(ShipmentTypeTransfer::KEY)
            ->setIsAscending(false);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $shipmentTypeCollectionIterator = $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator();
        $this->assertSame('ghi', $shipmentTypeCollectionIterator->offsetGet(0)->getKeyOrFail());
        $this->assertSame('def', $shipmentTypeCollectionIterator->offsetGet(1)->getKeyOrFail());
        $this->assertSame('abc', $shipmentTypeCollectionIterator->offsetGet(2)->getKeyOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesSortedByKeyFieldAsc(): void
    {
        // Arrange
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => 'abc']);
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => 'def']);
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => 'ghi']);

        $sortTransfer = (new SortTransfer())
            ->setField(ShipmentTypeTransfer::KEY)
            ->setIsAscending(true);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $shipmentTypeCollectionTransfer->getShipmentTypes());
        $shipmentTypeCollectionIterator = $shipmentTypeCollectionTransfer->getShipmentTypes()->getIterator();
        $this->assertSame('abc', $shipmentTypeCollectionIterator->offsetGet(0)->getKeyOrFail());
        $this->assertSame('def', $shipmentTypeCollectionIterator->offsetGet(1)->getKeyOrFail());
        $this->assertSame('ghi', $shipmentTypeCollectionIterator->offsetGet(2)->getKeyOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $expectedShipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $actualShipmentTypeTransfer
     *
     * @return void
     */
    protected function assertSameShipmentTypeTransfer(
        ShipmentTypeTransfer $expectedShipmentTypeTransfer,
        ShipmentTypeTransfer $actualShipmentTypeTransfer
    ): void {
        $this->assertSame($expectedShipmentTypeTransfer->getIdShipmentTypeOrFail(), $actualShipmentTypeTransfer->getIdShipmentType());
        $this->assertSame($expectedShipmentTypeTransfer->getUuidOrFail(), $actualShipmentTypeTransfer->getUuid());
        $this->assertSame($expectedShipmentTypeTransfer->getKeyOrFail(), $actualShipmentTypeTransfer->getKey());
        $this->assertSame($expectedShipmentTypeTransfer->getNameOrFail(), $actualShipmentTypeTransfer->getName());
        $this->assertSame($expectedShipmentTypeTransfer->getIsActiveOrFail(), $actualShipmentTypeTransfer->getIsActive());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected function findStoreTransferInStoreRelationTransfer(StoreRelationTransfer $storeRelationTransfer, string $storeName): ?StoreTransfer
    {
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            if ($storeTransfer->getNameOrFail() === $storeName) {
                return $storeTransfer;
            }
        }

        return null;
    }
}
