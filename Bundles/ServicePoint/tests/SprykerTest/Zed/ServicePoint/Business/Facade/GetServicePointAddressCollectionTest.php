<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointAddressConditionsTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\ServicePoint\ServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePoint
 * @group Business
 * @group Facade
 * @group GetServicePointAddressCollectionTest
 * Add your own group annotations below this line
 */
class GetServicePointAddressCollectionTest extends Unit
{
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
    public function testReturnsServicePointAddressesByServicePointUuids(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressConditionsTransfer = (new ServicePointAddressConditionsTransfer())
            ->addServicePointUuid($servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail());
        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->setServicePointAddressConditions($servicePointAddressConditionsTransfer);

        // Act
        $servicePointAddressCollectionTransfer = $this->tester->getFacade()
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionTransfer->getServicePointAddresses());
        /**
         * @var \Generated\Shared\Transfer\ServicePointAddressTransfer $retrievedServicePointAddressTransfer
         */
        $retrievedServicePointAddressTransfer = $servicePointAddressCollectionTransfer->getServicePointAddresses()->getIterator()->current();
        $this->assertSame(
            $servicePointAddressTransfer->getUuidOrFail(),
            $retrievedServicePointAddressTransfer->getUuidOrFail(),
        );
        $this->assertSame(
            $servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
            $retrievedServicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
        );
        $this->assertSame(
            $servicePointAddressTransfer->getCountryOrFail()->getIso2Code(),
            $retrievedServicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
        );
        $this->assertNull($servicePointAddressCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointAddressesByUuids(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressConditionsTransfer = (new ServicePointAddressConditionsTransfer())
            ->addUuid($servicePointAddressTransfer->getUuidOrFail());
        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->setServicePointAddressConditions($servicePointAddressConditionsTransfer);

        // Act
        $servicePointAddressCollectionTransfer = $this->tester->getFacade()
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);

        // Assert
        $this->assertCount(1, $servicePointAddressCollectionTransfer->getServicePointAddresses());
        /**
         * @var \Generated\Shared\Transfer\ServicePointAddressTransfer $retrievedServicePointAddressTransfer
         */
        $retrievedServicePointAddressTransfer = $servicePointAddressCollectionTransfer->getServicePointAddresses()->getIterator()->current();
        $this->assertSame(
            $servicePointAddressTransfer->getUuidOrFail(),
            $retrievedServicePointAddressTransfer->getUuidOrFail(),
        );
        $this->assertSame(
            $servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
            $retrievedServicePointAddressTransfer->getRegionOrFail()->getUuidOrFail(),
        );
        $this->assertSame(
            $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
            $retrievedServicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
        );
        $this->assertNull($servicePointAddressCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointAddressesByLimitAndOffset(): void
    {
        // Arrange
        $servicePointAddressCount = 4;
        for ($i = 0; $i < $servicePointAddressCount; $i++) {
            $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
            $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(1)
            ->setLimit(2);

        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setServicePointAddressConditions((new ServicePointAddressConditionsTransfer()));

        // Act
        $servicePointAddressCollectionTransfer = $this->tester->getFacade()
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);

        // Assert
        $this->assertCount(2, $servicePointAddressCollectionTransfer->getServicePointAddresses());
        $this->assertNotNull($servicePointAddressCollectionTransfer->getPagination());
        $this->assertSame($servicePointAddressCount, $servicePointAddressCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointAddressesByPagination(): void
    {
        // Arrange
        $servicePointAddressCount = 7;
        for ($i = 0; $i < $servicePointAddressCount; $i++) {
            $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
            $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(2);

        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setServicePointAddressConditions((new ServicePointAddressConditionsTransfer()));

        // Act
        $servicePointAddressCollectionTransfer = $this->tester->getFacade()
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);

        // Assert
        $this->assertCount(2, $servicePointAddressCollectionTransfer->getServicePointAddresses());
        $this->assertNotNull($servicePointAddressCollectionTransfer->getPaginationOrFail());

        $paginationTransfer = $servicePointAddressCollectionTransfer->getPaginationOrFail();

        $this->assertSame(2, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(7, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(3, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(4, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPageOrFail());
        $this->assertSame(4, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(4, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(3, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointAddressesSortedByFieldDesc(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([ServicePointAddressTransfer::ADDRESS1 => 'bac']);
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([ServicePointAddressTransfer::ADDRESS1 => 'abc']);
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([ServicePointAddressTransfer::ADDRESS1 => 'cab']);
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointAddressTransfer::ADDRESS1)
            ->setIsAscending(false);

        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setServicePointAddressConditions((new ServicePointAddressConditionsTransfer()));

        // Act
        $servicePointAddressCollectionTransfer = $this->tester->getFacade()
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);

        $servicePointAddressTransfers = $servicePointAddressCollectionTransfer->getServicePointAddresses();

        // Assert
        $this->assertCount(3, $servicePointAddressTransfers);
        $this->assertSame('cab', $servicePointAddressTransfers->getIterator()->offsetGet(0)->getAddress1OrFail());
        $this->assertSame('bac', $servicePointAddressTransfers->getIterator()->offsetGet(1)->getAddress1OrFail());
        $this->assertSame('abc', $servicePointAddressTransfers->getIterator()->offsetGet(2)->getAddress1OrFail());
    }

    /**
     * @return void
     */
    public function testReturnsServicePointAddressesSortedByFieldAsc(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([ServicePointAddressTransfer::ADDRESS1 => 'bac']);
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([ServicePointAddressTransfer::ADDRESS1 => 'abc']);
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([ServicePointAddressTransfer::ADDRESS1 => 'cab']);
        $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $sortTransfer = (new SortTransfer())
            ->setField(ServicePointAddressTransfer::ADDRESS1)
            ->setIsAscending(true);

        $servicePointAddressCriteriaTransfer = (new ServicePointAddressCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setServicePointAddressConditions((new ServicePointAddressConditionsTransfer()));

        // Act
        $servicePointAddressCollectionTransfer = $this->tester->getFacade()
            ->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);

        $servicePointAddressTransfers = $servicePointAddressCollectionTransfer->getServicePointAddresses();

        // Assert
        $this->assertCount(3, $servicePointAddressTransfers);
        $this->assertSame('abc', $servicePointAddressTransfers->getIterator()->offsetGet(0)->getAddress1OrFail());
        $this->assertSame('bac', $servicePointAddressTransfers->getIterator()->offsetGet(1)->getAddress1OrFail());
        $this->assertSame('cab', $servicePointAddressTransfers->getIterator()->offsetGet(2)->getAddress1OrFail());
    }
}
