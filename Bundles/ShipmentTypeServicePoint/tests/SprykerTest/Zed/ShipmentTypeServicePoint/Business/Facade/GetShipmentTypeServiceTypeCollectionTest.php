<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\Map\SpyShipmentTypeServiceTypeTableMap;
use SprykerTest\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeServicePoint
 * @group Business
 * @group Facade
 * @group GetShipmentTypeServiceTypeCollectionTest
 * Add your own group annotations below this line
 */
class GetShipmentTypeServiceTypeCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointBusinessTester
     */
    protected ShipmentTypeServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureShipmentTypeServiceTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsEmptyCollectionWhenDatabaseTableIsEmpty(): void
    {
        // Arrange
        $shipmentTypeServiceTypeCriteriaTransfer = new ShipmentTypeServiceTypeCriteriaTransfer();

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(0, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());
        $this->assertNull($shipmentTypeServiceTypeCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionWithAllPersistedRelationships(): void
    {
        // Arrange
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());

        $shipmentTypeServiceTypeCriteriaTransfer = new ShipmentTypeServiceTypeCriteriaTransfer();

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionFilteredByShipmentTypeIds(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $serviceTypeTransfer = $this->tester->haveServiceType();
        $this->tester->haveShipmentTypeServiceTypeRelation($shipmentTypeTransfer, $serviceTypeTransfer);
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());

        $shipmentTypeServiceTypeConditionsTransfer = (new ShipmentTypeServiceTypeConditionsTransfer())
            ->addIdShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail());
        $shipmentTypeServiceTypeCriteriaTransfer = (new ShipmentTypeServiceTypeCriteriaTransfer())
            ->setShipmentTypeServiceTypeConditions($shipmentTypeServiceTypeConditionsTransfer);

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeServiceTypeTransfer $shipmentTypeServiceTypeTransfer */
        $shipmentTypeServiceTypeTransfer = $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes()->getIterator()->current();
        $this->assertNotNull($shipmentTypeServiceTypeTransfer->getShipmentType());
        $this->assertNotNull($shipmentTypeServiceTypeTransfer->getServiceType());
        $this->assertSame(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfer->getShipmentTypeOrFail()->getIdShipmentType(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionExpandedWithServiceTypeData(): void
    {
        // Arrange
        $serviceTypeTransfer = $this->tester->haveServiceType();
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $serviceTypeTransfer);

        $shipmentTypeServiceTypeConditionsTransfer = (new ShipmentTypeServiceTypeConditionsTransfer())
            ->setWithServiceTypeRelations(true);
        $shipmentTypeServiceTypeCriteriaTransfer = (new ShipmentTypeServiceTypeCriteriaTransfer())
            ->setShipmentTypeServiceTypeConditions($shipmentTypeServiceTypeConditionsTransfer);

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());

        /** @var \Generated\Shared\Transfer\ShipmentTypeServiceTypeTransfer $shipmentTypeServiceTypeTransfer */
        $shipmentTypeServiceTypeTransfer = $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes()->getIterator()->current();
        $this->assertSame(
            $serviceTypeTransfer->toArray(),
            $shipmentTypeServiceTypeTransfer->getServiceType()->toArray(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());

        $shipmentTypeServiceTypeCriteriaTransfer = (new ShipmentTypeServiceTypeCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setOffset(1)->setLimit(2));

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());
        $this->assertSame(3, $shipmentTypeServiceTypeCollectionTransfer->getPaginationOrFail()->getNbResults());
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());
        $this->tester->haveShipmentTypeServiceTypeRelation($this->tester->haveShipmentType(), $this->tester->haveServiceType());

        $shipmentTypeServiceTypeCriteriaTransfer = (new ShipmentTypeServiceTypeCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setPage(2)->setMaxPerPage(2));

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());

        $paginationTransfer = $shipmentTypeServiceTypeCollectionTransfer->getPaginationOrFail();
        $this->assertSame(2, $paginationTransfer->getPage());
        $this->assertSame(2, $paginationTransfer->getMaxPerPage());
        $this->assertSame(4, $paginationTransfer->getNbResults());
        $this->assertSame(3, $paginationTransfer->getFirstIndex());
        $this->assertSame(4, $paginationTransfer->getLastIndex());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(2, $paginationTransfer->getLastPage());
        $this->assertSame(2, $paginationTransfer->getNextPage());
        $this->assertSame(1, $paginationTransfer->getPreviousPage());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionSortedByFkShipmentTypeFieldDesc(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer3 = $this->tester->haveShipmentType();
        $this->tester->createShipmentTypesServiceTypeRelations($this->tester->haveServiceType(), [
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
            $shipmentTypeTransfer3,
        ]);

        $shipmentTypeServiceTypeCriteriaTransfer = (new ShipmentTypeServiceTypeCriteriaTransfer())->addSort(
            (new SortTransfer())
                ->setField(SpyShipmentTypeServiceTypeTableMap::COL_FK_SHIPMENT_TYPE)
                ->setIsAscending(false),
        );

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());
        $shipmentTypeServiceTypeTransfers = $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes();
        $this->assertSame(
            $shipmentTypeTransfer3->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfers->offsetGet(0)->getShipmentType()->getIdShipmentType(),
        );
        $this->assertSame(
            $shipmentTypeTransfer2->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfers->offsetGet(1)->getShipmentType()->getIdShipmentType(),
        );
        $this->assertSame(
            $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfers->offsetGet(2)->getShipmentType()->getIdShipmentType(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCollectionSortedByFkShipmentTypeFieldAsc(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer3 = $this->tester->haveShipmentType();
        $this->tester->createShipmentTypesServiceTypeRelations($this->tester->haveServiceType(), [
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
            $shipmentTypeTransfer3,
        ]);

        $shipmentTypeServiceTypeCriteriaTransfer = (new ShipmentTypeServiceTypeCriteriaTransfer())->addSort(
            (new SortTransfer())
                ->setField(SpyShipmentTypeServiceTypeTableMap::COL_FK_SHIPMENT_TYPE)
                ->setIsAscending(true),
        );

        // Act
        $shipmentTypeServiceTypeCollectionTransfer = $this->tester->getFacade()
            ->getShipmentTypeServiceTypeCollection($shipmentTypeServiceTypeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes());
        $shipmentTypeServiceTypeTransfers = $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes();
        $this->assertSame(
            $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfers->offsetGet(0)->getShipmentType()->getIdShipmentType(),
        );
        $this->assertSame(
            $shipmentTypeTransfer2->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfers->offsetGet(1)->getShipmentType()->getIdShipmentType(),
        );
        $this->assertSame(
            $shipmentTypeTransfer3->getIdShipmentTypeOrFail(),
            $shipmentTypeServiceTypeTransfers->offsetGet(2)->getShipmentType()->getIdShipmentType(),
        );
    }
}
