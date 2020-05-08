<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitsRestApi\Business;

use Codeception\Test\Unit;

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
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithSalesUnitData(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithSalesUnitData();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->tester->getFacade()
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
        $persistentCartChangeTransfer = $this->tester->getFacade()
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
        $persistentCartChangeTransfer = $this->tester->getFacade()
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
}
