<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePoint
 * @group Business
 * @group Facade
 * @group GetProductOfferServiceCollectionTest
 * Add your own group annotations below this line
 */
class GetProductOfferServiceCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const UNKNOWN_SERVICE_ID = -1;

    /**
     * @var int
     */
    protected const PRODUCT_OFFER_SERVICE_COUNT = 4;

    /**
     * @uses \Orm\Zed\ProductOfferServicePoint\Persistence\Map\SpyProductOfferServiceTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_service.fk_product_offer';

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester
     */
    protected ProductOfferServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceTableAndRelationsAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyProductOfferServiceCollection(): void
    {
        // Arrange
        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->addIdService(static::UNKNOWN_SERVICE_ID);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            0,
            $productOfferServiceCollectionTransfer->getProductOfferServices(),
        );

        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionByServiceIds(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->addIdService($serviceTransfer->getIdServiceOrFail());

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());
        $this->assertCollectionHasServiceAndProductOffer(
            $productOfferServiceCollectionTransfer,
            $serviceTransfer,
            $productOfferTransfer,
            1,
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionByProductOfferIds(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail());

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());
        $this->assertCollectionHasServiceAndProductOffer(
            $productOfferServiceCollectionTransfer,
            $serviceTransfer,
            $productOfferTransfer,
            1,
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionByProductOfferServiceIds(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferServiceTransfer = $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $idProductOfferService = $this->tester->findIdProductOfferService($productOfferServiceTransfer);

        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->addIdProductOfferService($idProductOfferService);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());
        $this->assertSame(
            $idProductOfferService,
            $productOfferServiceCollectionTransfer
                ->getProductOfferServices()
                ->getIterator()
                ->current()
                ->getIdProductOfferService(),
        );
        $this->assertCollectionHasServiceAndProductOffer(
            $productOfferServiceCollectionTransfer,
            $serviceTransfer,
            $productOfferTransfer,
            1,
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionWithServicePointRelations(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferServiceTransfer = $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $idProductOfferService = $this->tester->findIdProductOfferService($productOfferServiceTransfer);

        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->addIdProductOfferService($idProductOfferService)
            ->setWithServicePointRelations(true);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServiceCollectionTransfer->getProductOfferServices());
        /** @var \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer */
        $productOfferServicesTransfer = $productOfferServiceCollectionTransfer->getProductOfferServices()->getIterator()->current();

        foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
            $this->assertNotNull($serviceTransfer->getServicePoint());
            $this->assertNotNull($serviceTransfer->getServicePoint()->getUuid());
            $this->assertNotNull($serviceTransfer->getServicePoint()->getIdServicePoint());
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionGroupedByIdProductOffer(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondServiceTransfer = $this->tester->haveService();
        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->setServiceIds([
                $serviceTransfer->getIdServiceOrFail(), $secondServiceTransfer->getIdServiceOrFail(),
            ])
            ->setGroupByIdProductOffer(true);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());
        $this->assertCount(1, $productOfferServiceCollectionTransfer->getProductOfferServices());

        /** @var \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer */
        $productOfferServicesTransfer = $productOfferServiceCollectionTransfer->getProductOfferServices()->getIterator()->current();

        $this->assertNull($productOfferServicesTransfer->getIdProductOfferService());
        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail(),
        );
        $this->assertCount(2, $productOfferServicesTransfer->getServices());
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionPaginatedByOffsetAndLimit(): void
    {
        // Arrange
        $this->sortProductOfferServiceTransfers(
            $this->tester->createProductOfferServiceTransfers(static::PRODUCT_OFFER_SERVICE_COUNT),
            true,
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit(2);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $productOfferServiceCollectionTransfer->getProductOfferServices(),
        );

        $this->assertNotNull($productOfferServiceCollectionTransfer->getPagination());

        $this->assertSame(
            static::PRODUCT_OFFER_SERVICE_COUNT,
            $productOfferServiceCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionPaginatedByPage(): void
    {
        // Arrange
        $this->sortProductOfferServiceTransfers(
            $this->tester->createProductOfferServiceTransfers(static::PRODUCT_OFFER_SERVICE_COUNT),
            true,
        );

        $paginationTransfer = (new PaginationTransfer())->setPage(2)->setMaxPerPage(2);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->setPagination($paginationTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            2,
            $productOfferServiceCollectionTransfer->getProductOfferServices(),
        );

        $this->assertNotNull($productOfferServiceCollectionTransfer->getPagination());

        $this->assertSame(
            static::PRODUCT_OFFER_SERVICE_COUNT,
            $productOfferServiceCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $productOfferServiceTransfersSorted = $this->sortProductOfferServiceTransfers(
            $this->tester->createProductOfferServiceTransfers(static::PRODUCT_OFFER_SERVICE_COUNT),
            true,
        );

        $sortTransfer = (new SortTransfer())
            ->setField(static::COL_FK_PRODUCT_OFFER)
            ->setIsAscending(true);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            count($productOfferServiceTransfersSorted),
            $productOfferServiceCollectionTransfer->getProductOfferServices(),
        );

        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());

        foreach ($productOfferServiceTransfersSorted as $index => $productOfferServiceTransfer) {
            $this->assertSame(
                $productOfferServiceTransfer->getIdProductOfferOrFail(),
                $productOfferServiceCollectionTransfer->getProductOfferServices()
                    ->getIterator()
                    ->offsetGet($index)
                    ->getProductOfferOrFail()
                    ->getIdProductOfferOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServiceCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $productOfferServiceTransfersSorted = $this->sortProductOfferServiceTransfers(
            $this->tester->createProductOfferServiceTransfers(static::PRODUCT_OFFER_SERVICE_COUNT),
            false,
        );

        $sortTransfer = (new SortTransfer())
            ->setField(static::COL_FK_PRODUCT_OFFER)
            ->setIsAscending(false);

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->addSort($sortTransfer);

        // Act
        $productOfferServiceCollectionTransfer = $this->tester
            ->getFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

        // Assert
        $this->assertCount(
            static::PRODUCT_OFFER_SERVICE_COUNT,
            $productOfferServiceCollectionTransfer->getProductOfferServices(),
        );

        $this->assertNull($productOfferServiceCollectionTransfer->getPagination());

        foreach ($productOfferServiceTransfersSorted as $index => $productOfferServiceTransfer) {
            $this->assertSame(
                $productOfferServiceTransfer->getIdProductOfferOrFail(),
                $productOfferServiceCollectionTransfer->getProductOfferServices()
                    ->getIterator()
                    ->offsetGet($index)
                    ->getProductOfferOrFail()
                    ->getIdProductOfferOrFail(),
            );
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServiceTransfer> $productOfferServiceTransfers
     * @param bool $isAscending
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServiceTransfer>
     */
    protected function sortProductOfferServiceTransfers(array $productOfferServiceTransfers, bool $isAscending): array
    {
        $productOfferIdsSortingCallback = function (
            ProductOfferServiceTransfer $productOfferServiceTransferFirst,
            ProductOfferServiceTransfer $productOfferServiceTransferSecond
        ) use ($isAscending) {
            if (!$isAscending) {
                return strcmp(
                    $productOfferServiceTransferSecond->getIdProductOfferOrFail(),
                    $productOfferServiceTransferFirst->getIdProductOfferOrFail(),
                );
            }

            return strcmp(
                $productOfferServiceTransferFirst->getIdProductOfferOrFail(),
                $productOfferServiceTransferSecond->getIdProductOfferOrFail(),
            );
        };

        usort($productOfferServiceTransfers, $productOfferIdsSortingCallback);

        return $productOfferServiceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param int $count
     *
     * @return void
     */
    protected function assertCollectionHasServiceAndProductOffer(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer,
        ServiceTransfer $serviceTransfer,
        ProductOfferTransfer $productOfferTransfer,
        int $count
    ): void {
        $this->assertCount($count, $productOfferServiceCollectionTransfer->getProductOfferServices());

        /** @var \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer */
        $productOfferServicesTransfer = $productOfferServiceCollectionTransfer
            ->getProductOfferServices()
            ->getIterator()
            ->current();

        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail(),
        );

        /** @var \Generated\Shared\Transfer\ServiceTransfer $resultServiceTransfer */
        $resultServiceTransfer = $productOfferServicesTransfer->getServices()->getIterator()->current();
        $this->assertSame($serviceTransfer->getIdServiceOrFail(), $resultServiceTransfer->getIdServiceOrFail());
    }
}
