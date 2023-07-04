<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap;
use SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentType
 * @group Business
 * @group Facade
 * @group GetProductOfferShipmentTypeCollectionTest
 * Add your own group annotations below this line
 */
class GetProductOfferShipmentTypeCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester
     */
    protected ProductOfferShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getProductOfferShipmentTypeQuery());
    }

    /**
     * @return void
     */
    public function testReturnsEmptyCollectionWhenThereAreNoProductOfferShipmentTypeRelations(): void
    {
        // Arrange
        $this->tester->haveProductOffer();
        $this->tester->haveShipmentType();

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer());

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
    }

    /**
     * @return void
     */
    public function testReturnsAllProductOfferShipmentTypeRelationsWhenNoFiltersProvided(): void
    {
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer2);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer2);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer());

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(4, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionFilteredByProductOfferShipmentTypeId(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $productOfferShipmentTypeTransfer = $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setProductOfferShipmentTypeConditions(
            (new ProductOfferShipmentTypeConditionsTransfer())
                ->addIdProductOfferShipmentType($productOfferShipmentTypeTransfer->getIdProductOfferShipmentTypeOrFail()),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $this->assertSame(
            $productOfferShipmentTypeTransfer->getIdProductOfferShipmentTypeOrFail(),
            $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->getIterator()->current()->getIdProductOfferShipmentType(),
        );
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->getIterator()->current(),
            $productOfferTransfer,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionFilteredByProductOfferId(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setProductOfferShipmentTypeConditions(
            (new ProductOfferShipmentTypeConditionsTransfer())->addIdProductOffer(
                $productOfferTransfer->getIdProductOfferOrFail(),
            ),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $productOfferShipmentTypeIterator = $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->getIterator();
        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $productOfferShipmentTypeIterator->current()->getProductOffer()->getIdProductOffer(),
        );
        $productOfferShipmentTypeIterator->next();
        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $productOfferShipmentTypeIterator->current()->getProductOffer()->getIdProductOffer(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionGroupedByProductOfferId(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setProductOfferShipmentTypeConditions(
            (new ProductOfferShipmentTypeConditionsTransfer())
                ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail())
                ->setGroupByIdProductOffer(true),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());

        $productOfferShipmentTypeTransfer = $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->getIterator()->current();
        $this->assertNotNull($productOfferShipmentTypeTransfer->getProductOffer());
        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfer->getProductOffer()->getIdProductOffer(),
        );
        $this->assertCount(2, $productOfferShipmentTypeTransfer->getShipmentTypes());
        $this->assertNotNull($this->findShipmentTypeByIdShipmentType(
            $productOfferShipmentTypeTransfer->getShipmentTypes(),
            $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
        ));
        $this->assertNotNull($this->findShipmentTypeByIdShipmentType(
            $productOfferShipmentTypeTransfer->getShipmentTypes(),
            $shipmentTypeTransfer2->getIdShipmentTypeOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionFilteredByShipmentTypeId(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setProductOfferShipmentTypeConditions(
            (new ProductOfferShipmentTypeConditionsTransfer())->addIdShipmentType(
                $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
            ),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->getIterator()->current(),
            $productOfferTransfer,
            $shipmentTypeTransfer1,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $productOfferTransfer3 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer3, $shipmentTypeTransfer);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setPagination(
            (new PaginationTransfer())->setOffset(1)->setLimit(2),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $this->assertNotNull($productOfferShipmentTypeCollectionTransfer->getPagination());
        $this->assertSame(3, $productOfferShipmentTypeCollectionTransfer->getPaginationOrFail()->getNbResults());
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeCollectionPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $productOfferTransfer3 = $this->tester->haveProductOffer();
        $productOfferTransfer4 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer3, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer4, $shipmentTypeTransfer);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setPagination(
            (new PaginationTransfer())->setPage(2)->setMaxPerPage(2),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $this->assertNotNull($productOfferShipmentTypeCollectionTransfer->getPagination());

        $paginationTransfer = $productOfferShipmentTypeCollectionTransfer->getPaginationOrFail();
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
    public function testReturnsProductOfferShipmentTypeCollectionSortedByProductOfferReferenceFieldDesc(): void
    {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $productOfferTransfer3 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer3, $shipmentTypeTransfer);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->addSort(
            (new SortTransfer())
                ->setField(SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER)
                ->setIsAscending(false),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $productOfferShipmentTypeTransfers = $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes();
        $this->assertSame(
            $productOfferTransfer3->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfers->offsetGet(0)->getProductOfferOrFail()->getIdProductOffer(),
        );
        $this->assertSame(
            $productOfferTransfer2->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfers->offsetGet(1)->getProductOfferOrFail()->getIdProductOffer(),
        );
        $this->assertSame(
            $productOfferTransfer1->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfers->offsetGet(2)->getProductOfferOrFail()->getIdProductOffer(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsShipmentTypesSortedByKeyFieldAsc(): void
    {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $productOfferTransfer3 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer3, $shipmentTypeTransfer);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->addSort(
            (new SortTransfer())
                ->setField(SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER)
                ->setIsAscending(true),
        );

        // Act
        $productOfferShipmentTypeCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes());
        $productOfferShipmentTypeTransfers = $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes();
        $this->assertSame(
            $productOfferTransfer1->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfers->offsetGet(0)->getProductOfferOrFail()->getIdProductOffer(),
        );
        $this->assertSame(
            $productOfferTransfer2->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfers->offsetGet(1)->getProductOfferOrFail()->getIdProductOffer(),
        );
        $this->assertSame(
            $productOfferTransfer3->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfers->offsetGet(2)->getProductOfferOrFail()->getIdProductOffer(),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmenTypeTransfers
     * @param int $idShipmentType
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer|null
     */
    protected function findShipmentTypeByIdShipmentType(ArrayObject $shipmenTypeTransfers, int $idShipmentType): ?ShipmentTypeTransfer
    {
        foreach ($shipmenTypeTransfers as $shipmenTypeTransfer) {
            if ($shipmenTypeTransfer->getIdShipmentTypeOrFail() === $idShipmentType) {
                return $shipmenTypeTransfer;
            }
        }

        return null;
    }
}
