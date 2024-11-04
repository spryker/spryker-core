<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentType
 * @group Business
 * @group Facade
 * @group GetProductOfferShipmentTypesIteratorTest
 * Add your own group annotations below this line
 */
class GetProductOfferShipmentTypesIteratorTest extends Unit
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
    public function testReturnsNoDataWhenThereIsNoProductOfferShipmentTypeRelations(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveShipmentType();

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail());
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(0, $productOfferShipmentTypeTransfers);
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTransfersInBatches(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getProductOfferShipmentTypeReadBatchSize', 1);

        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->setProductOfferIds([
                $productOfferTransfer1->getIdProductOfferOrFail(),
                $productOfferTransfer2->getIdProductOfferOrFail(),
            ]);
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $this->assertCount(2, iterator_to_array($iterator));
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersByProductOfferIds(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail());
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeTransfers[0],
            $productOfferTransfer,
            $shipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersFilteredByProductOfferApprovalStatus(): void
    {
        // Arrange
        $approvedProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
        ]);
        $deniedProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED,
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($approvedProductOfferTransfer, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($deniedProductOfferTransfer, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->setProductOfferIds([
                $approvedProductOfferTransfer->getIdProductOfferOrFail(),
                $deniedProductOfferTransfer->getIdProductOfferOrFail(),
            ])
            ->addProductOfferApprovalStatus(static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED);
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeTransfers[0],
            $approvedProductOfferTransfer,
            $shipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersFilteredByProductOfferIsActiveStatus(): void
    {
        // Arrange
        $inactiveProductOfferTransfer = $this->tester->haveProductOffer([ProductOfferTransfer::IS_ACTIVE => false]);
        $activeProductOfferTransfer = $this->tester->haveProductOffer([ProductOfferTransfer::IS_ACTIVE => true]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($inactiveProductOfferTransfer, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($activeProductOfferTransfer, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->setProductOfferIds([
                $inactiveProductOfferTransfer->getIdProductOfferOrFail(),
                $activeProductOfferTransfer->getIdProductOfferOrFail(),
            ])
            ->setIsActiveProductOffer(true);
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeTransfers[0],
            $activeProductOfferTransfer,
            $shipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersFilteredByProductOfferProductConcreteIsActiveStatus(): void
    {
        // Arrange
        $activeProductConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::IS_ACTIVE => true]);
        $inactiveProductConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::IS_ACTIVE => false]);
        $productOfferTransfer1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $activeProductConcreteTransfer->getSkuOrFail(),
        ]);
        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $inactiveProductConcreteTransfer->getSkuOrFail(),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->setProductOfferIds([
                $productOfferTransfer1->getIdProductOfferOrFail(),
                $productOfferTransfer2->getIdProductOfferOrFail(),
            ])
            ->setIsActiveProductOfferConcreteProduct(false);
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeTransfers[0],
            $productOfferTransfer2,
            $shipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersFilteredByShipmentTypeIsActiveStatus(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $activeShipmentTypeTransfer = $this->tester->haveShipmentType([ShipmentTypeTransfer::IS_ACTIVE => true]);
        $inactiveShipmentTypeTransfer = $this->tester->haveShipmentType([ShipmentTypeTransfer::IS_ACTIVE => false]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $activeShipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $inactiveShipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail())
            ->setIsActiveShipmentType(true);
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $this->tester->assertProductOfferShipmentTypeTransfer(
            $productOfferShipmentTypeTransfers[0],
            $productOfferTransfer,
            $activeShipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersExpandedWithProductOffers(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail());
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $retrievedProductOfferTransfer = $productOfferShipmentTypeTransfers[0]->getProductOffer();
        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $retrievedProductOfferTransfer->getIdProductOffer(),
        );
        $this->assertSame(
            $productOfferTransfer->getApprovalStatusOrFail(),
            $retrievedProductOfferTransfer->getApprovalStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsProductOfferShipmentTypeTransfersExpandedWithShipmentTypes(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $iterableProductOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->addIdProductOffer($productOfferTransfer->getIdProductOfferOrFail());
        $iterableProductOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($iterableProductOfferShipmentTypeConditionsTransfer);

        // Act
        $iterator = $this->tester->getFacade()
            ->getProductOfferShipmentTypesIterator($iterableProductOfferShipmentTypeCriteriaTransfer);

        // Assert
        $productOfferShipmentTypeTransfers = $this->getProductOfferTransfers($iterator);
        $this->assertCount(1, $productOfferShipmentTypeTransfers);
        $retrievedShipmentTypeTransfer = $productOfferShipmentTypeTransfers[0]->getShipmentTypes()->getIterator()->current();
        $this->assertNotNull($retrievedShipmentTypeTransfer);
        $this->assertSame($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $retrievedShipmentTypeTransfer->getIdShipmentType());
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $retrievedShipmentTypeTransfer->getName());
    }

    /**
     * @param iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>> $productOfferShipmentTypeIterator
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>
     */
    protected function getProductOfferTransfers(iterable $productOfferShipmentTypeIterator): array
    {
        $mergedProductOfferShipmentTypeTransfers = [];
        foreach ($productOfferShipmentTypeIterator as $productOfferShipmentTypeTransfers) {
            $mergedProductOfferShipmentTypeTransfers[] = $productOfferShipmentTypeTransfers->getArrayCopy();
        }

        return array_merge(...$mergedProductOfferShipmentTypeTransfers);
    }
}
