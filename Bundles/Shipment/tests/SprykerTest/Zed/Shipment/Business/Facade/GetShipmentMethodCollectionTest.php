<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodConditionsTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\Shipment\ShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group GetShipmentMethodCollectionTest
 * Add your own group annotations below this line
 */
class GetShipmentMethodCollectionTest extends Unit
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
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureShipmentMethodTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodByIdShipmentMethod(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->addIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail());
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentMethods());
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeCollectionTransfer->getShipmentMethods()->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodByIdShipmentCarrier(): void
    {
        // Arrange
        $shipmentCarrierTransfer = $this->tester->haveShipmentCarrier();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::FK_SHIPMENT_CARRIER => $shipmentCarrierTransfer->getIdShipmentCarrierOrFail(),
        ]);
        $this->tester->haveShipmentMethod();

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->addIdShipmentCarrier($shipmentCarrierTransfer->getIdShipmentCarrierOrFail());
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentMethods());
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeCollectionTransfer->getShipmentMethods()->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodByIsActiveStatus(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::IS_ACTIVE => true,
        ]);
        $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::IS_ACTIVE => false,
        ]);

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->setIsActive(true);
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentMethods());
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeCollectionTransfer->getShipmentMethods()->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodByIsActiveShipmentCarrierStatus(): void
    {
        // Arrange
        $activeShipmentCarrierTransfer = $this->tester->haveShipmentCarrier([
            ShipmentCarrierTransfer::IS_ACTIVE => true,
        ]);
        $inactiveShipmentCarrierTransfer = $this->tester->haveShipmentCarrier([
            ShipmentCarrierTransfer::IS_ACTIVE => false,
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::FK_SHIPMENT_CARRIER => $inactiveShipmentCarrierTransfer->getIdShipmentCarrierOrFail(),
        ]);
        $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::FK_SHIPMENT_CARRIER => $activeShipmentCarrierTransfer->getIdShipmentCarrierOrFail(),
        ]);

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->setIsActiveShipmentCarrier(false);
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentMethods());
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeCollectionTransfer->getShipmentMethods()->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodByStoreName(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE], false);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT], false);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], [], [$storeTransferDe->getIdStoreOrFail()]);
        $this->tester->haveShipmentMethod([], [], [], [$storeTransferAt->getIdStoreOrFail()]);

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->addStoreName($storeTransferDe->getNameOrFail());
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentTypeCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentTypeCollectionTransfer->getShipmentMethods());
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeCollectionTransfer->getShipmentMethods()->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodByStoreNamesWithoutDuplicates(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE], false);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT], false);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], [], [
            $storeTransferDe->getIdStoreOrFail(),
            $storeTransferAt->getIdStoreOrFail(),
        ]);

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->addStoreName($storeTransferDe->getNameOrFail())
            ->addStoreName($storeTransferAt->getNameOrFail());
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentMethodCollectionTransfer->getShipmentMethods()->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsCorrectShipmentMethodsByStoreNamesWithoutDuplicates(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE], false);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT], false);
        $shipmentMethodTransfer1 = $this->tester->haveShipmentMethod([], [], [], [
            $storeTransferDe->getIdStoreOrFail(),
            $storeTransferAt->getIdStoreOrFail(),
        ]);
        $shipmentMethodTransfer2 = $this->tester->haveShipmentMethod([], [], [], [
            $storeTransferDe->getIdStoreOrFail(),
        ]);

        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->addStoreName($storeTransferDe->getNameOrFail())
            ->addStoreName($storeTransferAt->getNameOrFail());
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())
            ->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $this->assertNotNull(
            $this->findShipmentMethodTransferInShipmentMethodCollectionTransfer(
                $shipmentMethodCollectionTransfer,
                $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            ),
        );
        $this->assertNotNull(
            $this->findShipmentMethodTransferInShipmentMethodCollectionTransfer(
                $shipmentMethodCollectionTransfer,
                $shipmentMethodTransfer2->getIdShipmentMethodOrFail(),
            ),
        );
    }

    /**
     * @return void
     */
    public function testReturnsShipmentMethodsPaginatedByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(1)
            ->setLimit(2);

        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $this->assertNotNull($shipmentMethodCollectionTransfer->getPagination());
        $this->assertSame(4, $shipmentMethodCollectionTransfer->getPaginationOrFail()->getNbResults());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentMethodsPaginatedByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();
        $this->tester->haveShipmentMethod();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $this->assertNotNull($shipmentMethodCollectionTransfer->getPagination());
        $this->assertSame(4, $shipmentMethodCollectionTransfer->getPaginationOrFail()->getNbResults());

        $paginationTransfer = $shipmentMethodCollectionTransfer->getPaginationOrFail();

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
    public function testReturnsShipmentMethodsSortedByKeyFieldDesc(): void
    {
        // Arrange
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'abc']);
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'def']);
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'ghi']);

        $sortTransfer = (new SortTransfer())
            ->setField(ShipmentMethodTransfer::SHIPMENT_METHOD_KEY)
            ->setIsAscending(false);

        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(3, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $shipmentMethodCollectionIterator = $shipmentMethodCollectionTransfer->getShipmentMethods()->getIterator();
        $this->assertSame('ghi', $shipmentMethodCollectionIterator->offsetGet(0)->getShipmentMethodKey());
        $this->assertSame('def', $shipmentMethodCollectionIterator->offsetGet(1)->getShipmentMethodKey());
        $this->assertSame('abc', $shipmentMethodCollectionIterator->offsetGet(2)->getShipmentMethodKey());
    }

    /**
     * @return void
     */
    public function testReturnsShipmentMethodsSortedByKeyFieldAsc(): void
    {
        // Arrange
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'abc']);
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'def']);
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'ghi']);

        $sortTransfer = (new SortTransfer())
            ->setField(ShipmentMethodTransfer::SHIPMENT_METHOD_KEY)
            ->setIsAscending(true);

        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(3, $shipmentMethodCollectionTransfer->getShipmentMethods());
        $shipmentMethodCollectionIterator = $shipmentMethodCollectionTransfer->getShipmentMethods()->getIterator();
        $this->assertSame('abc', $shipmentMethodCollectionIterator->offsetGet(0)->getShipmentMethodKey());
        $this->assertSame('def', $shipmentMethodCollectionIterator->offsetGet(1)->getShipmentMethodKey());
        $this->assertSame('ghi', $shipmentMethodCollectionIterator->offsetGet(2)->getShipmentMethodKey());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethodTransferInShipmentMethodCollectionTransfer(
        ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer,
        int $idShipmentMethod
    ): ?ShipmentMethodTransfer {
        foreach ($shipmentMethodCollectionTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethodOrFail() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }
}
