<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitsRestApi\Business;

use Codeception\Test\Unit;
use Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiBusinessFactory;
use Spryker\Zed\ProductMeasurementUnitsRestApi\Dependency\Facade\ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnitsRestApi
 * @group Business
 * @group Facade
 * @group ProductMeasurementUnitsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ProductMeasurementUnitsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiFacadeInterface
     */
    protected $productMeasurementUnitsRestApiFacade;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiFacadeInterface
     */
    protected $productMeasurementUnitsRestApiFacadeWithNoProductPackagingUnitFound;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productMeasurementUnitsRestApiFacade = $this->tester->getFacade()
            ->setFactory($this->getMockProductMeasurementUnitsRestApiBusinessFactory());
        $this->productMeasurementUnitsRestApiFacadeWithNoProductPackagingUnitFound = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithSalesUnitData(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithSalesUnitData();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->productMeasurementUnitsRestApiFacade
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertNotNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmountSalesUnit()
        );

        $this->tester->assertNotNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmount()
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithoutSalesUnitData(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithOutSalesUnitData();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->productMeasurementUnitsRestApiFacade
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmountSalesUnit()
        );

        $this->tester->assertNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmount()
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithDifferentSku(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithSalesUnitData();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransferWithDifferentSku();

        // Act
        $persistentCartChangeTransfer = $this->productMeasurementUnitsRestApiFacade
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmountSalesUnit()
        );

        $this->tester->assertNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmount()
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithoutProductPackagingUnit(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithSalesUnitData();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransferWithDifferentSku();

        // Act
        $persistentCartChangeTransfer = $this->productMeasurementUnitsRestApiFacadeWithNoProductPackagingUnitFound
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmountSalesUnit()
        );

        $this->tester->assertNull(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getAmount()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockProductMeasurementUnitsRestApiBusinessFactory(): ProductMeasurementUnitsRestApiBusinessFactory
    {
        $productMeasurementUnitsRestApiBusinessFactoryMock = $this->createPartialMock(
            ProductMeasurementUnitsRestApiBusinessFactory::class,
            [
                'getProductPackagingUnitFacade',
            ]
        );

        return $this->addMockProductPackagingUnitFacade($productMeasurementUnitsRestApiBusinessFactoryMock);
    }
    
    /**
     * @param \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $productMeasurementUnitsRestApiBusinessFactoryMock
     *
     * @return \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\ProductMeasurementUnitsRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockProductPackagingUnitFacade(ProductMeasurementUnitsRestApiBusinessFactory $productMeasurementUnitsRestApiBusinessFactoryMock): ProductMeasurementUnitsRestApiBusinessFactory
    {
        $productPackagingUnitFacadeMock = $this->createPartialMock(
            ProductPackagingUnitFacade::class,
            [
                'findProductPackagingUnitByProductSku',
            ]
        );

        $productPackagingUnitFacadeMock->method('findProductPackagingUnitByProductSku')
            ->willReturn($this->tester->buildProductPackagingUnitTransfer());
        $productMeasurementUnitsRestApiBusinessFactoryMock->method('getProductPackagingUnitFacade')
            ->willReturn((new ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeBridge($productPackagingUnitFacadeMock)));

        return $productMeasurementUnitsRestApiBusinessFactoryMock;
    }
}
