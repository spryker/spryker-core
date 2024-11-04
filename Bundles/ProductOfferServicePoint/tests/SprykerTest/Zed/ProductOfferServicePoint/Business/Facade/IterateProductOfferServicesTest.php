<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IterableProductOfferServicesConditionsTransfer;
use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePoint
 * @group Business
 * @group Facade
 * @group IterateProductOfferServicesTest
 * Add your own group annotations below this line
 */
class IterateProductOfferServicesTest extends Unit
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_DENIED = 'denied';

    /**
     * @var int
     */
    protected const ID_PRODUCT_OFFER_INVALID = -1;

    /**
     * @var int
     */
    protected const ID_SERVICE_INVALID = -1;

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
    public function testShouldThrowExceptionWhenConditionsAreNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->getProductOfferServicesTransfers(new IterableProductOfferServicesCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyProductOfferServicesCollection(): void
    {
        // Arrange
        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->addIdProductOffer(static::ID_PRODUCT_OFFER_INVALID);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productOfferServicesTransfers);
    }

    /**
     * @return void
     */
    public function testShouldReturnProductCollectionByChunks(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getProductOfferServicesProcessBatchSize', 1);

        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondServiceTransfer = $this->tester->haveService();
        $secondProductOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())->setProductOfferIds([
            $productOfferTransfer->getIdProductOfferOrFail(),
            $secondProductOfferTransfer->getIdProductOfferOrFail(),
        ]);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()->iterateProductOfferServices($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(2, iterator_to_array($iterator));
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionByProductOfferIds(): void
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

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail());

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$serviceTransfer->getIdServiceOrFail(), $secondServiceTransfer->getIdServiceOrFail()],
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionWithServicePointRelations(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail())
            ->setWithServicePointRelations(true);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$serviceTransfer->getIdServiceOrFail()],
            $productOfferTransfer->getIdProductOfferOrFail(),
        );

        /** @var \Generated\Shared\Transfer\ServiceTransfer $resultServiceTransfer */
        $resultServiceTransfer = $productOfferServicesTransfers[0]->getServices()->getIterator()->current();
        $this->assertNotNull($resultServiceTransfer->getServicePoint());
        $this->assertSame($serviceTransfer->getServicePoint()->getIdServicePoint(), $resultServiceTransfer->getServicePoint()->getIdServicePoint());
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionByIsServiceActive(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondServiceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail())
            ->setIsActiveService(true);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$secondServiceTransfer->getIdServiceOrFail()],
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionByIsServicePointActive(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->haveServicePoint([ServicePointTransfer::IS_ACTIVE => true]);
        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $servicePointTransfer = $this->tester->haveServicePoint([ServicePointTransfer::IS_ACTIVE => false]);
        $secondServiceTransfer = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail())
            ->setIsActiveServicePoint(true);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert

        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$serviceTransfer->getIdServiceOrFail()],
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionByIsProductOfferActive(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => false,
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->setProductOfferIds([
                $productOfferTransfer->getIdProductOfferOrFail(),
                $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ])
            ->setIsActiveProductOffer(true);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$serviceTransfer->getIdServiceOrFail()],
            $secondProductOfferTransfer->getIdProductOfferOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionByProductOfferApprovalStatuses(): void
    {
        // Arrange
        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondServiceTransfer = $this->tester->haveService();
        $secondProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED,
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->setProductOfferIds([
                $productOfferTransfer->getIdProductOfferOrFail(),
                $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ])
            ->addProductOfferApprovalStatus(static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$secondServiceTransfer->getIdServiceOrFail()],
            $secondProductOfferTransfer->getIdProductOfferOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferServicesCollectionByIsActiveConcreteProduct(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productConcreteTransfer = $this->tester->haveProductConcrete([
            ProductConcreteTransfer::IS_ACTIVE => false,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);

        $serviceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSkuOrFail(),
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondProductConcreteTransfer = $this->tester->haveProductConcrete([
            ProductConcreteTransfer::IS_ACTIVE => true,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);
        $secondServiceTransfer = $this->tester->haveService();
        $secondProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $secondProductConcreteTransfer->getSkuOrFail(),
        ]);

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->setProductOfferIds([
                $productOfferTransfer->getIdProductOfferOrFail(),
                $secondProductOfferTransfer->getIdProductOfferOrFail(),
            ])
            ->setIsActiveConcreteProduct(true);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        // Act
        $productOfferServicesTransfers = $this->getProductOfferServicesTransfers($iterableProductOfferServicesCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicesTransfers);
        $this->assertProductOfferServicesTransfer(
            $productOfferServicesTransfers[0],
            [$secondServiceTransfer->getIdServiceOrFail()],
            $secondProductOfferTransfer->getIdProductOfferOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer
     * @param list<int> $expectedServiceIds
     * @param int $expectedIdProductOffer
     *
     * @return void
     */
    protected function assertProductOfferServicesTransfer(
        ProductOfferServicesTransfer $productOfferServicesTransfer,
        array $expectedServiceIds,
        int $expectedIdProductOffer
    ): void {
        $this->assertSame($expectedIdProductOffer, $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail());

        $this->assertCount(count($expectedServiceIds), $productOfferServicesTransfer->getServices());
        foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
            $this->assertTrue(in_array($serviceTransfer->getIdServiceOrFail(), $expectedServiceIds));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    protected function getProductOfferServicesTransfers(IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer): array
    {
        $productOfferServicesTransfers = [];

        $iterator = $this->tester->getFacade()->iterateProductOfferServices($iterableProductOfferServicesCriteriaTransfer);
        foreach ($iterator as $data) {
            $productOfferServicesTransfers += $data;
        }

        return $productOfferServicesTransfers;
    }
}
