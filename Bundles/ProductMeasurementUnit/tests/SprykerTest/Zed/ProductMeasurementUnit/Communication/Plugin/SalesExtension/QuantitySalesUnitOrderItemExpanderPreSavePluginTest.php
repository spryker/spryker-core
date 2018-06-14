<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension\QuantitySalesUnitOrderItemExpanderPreSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Communication
 * @group Plugin
 * @group SalesExtension
 * @group QuantitySalesUnitOrderItemExpanderPreSavePluginTest
 * Add your own group annotations below this line
 */
class QuantitySalesUnitOrderItemExpanderPreSavePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension\QuantitySalesUnitOrderItemExpanderPreSavePlugin
     */
    protected $quantitySalesUnitOrderItemExpanderPreSavePlugin;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->quantitySalesUnitOrderItemExpanderPreSavePlugin = new QuantitySalesUnitOrderItemExpanderPreSavePlugin();
    }

    /**
     * @return void
     */
    public function testExpandOrderItemAddsMeasurementUnitInfo(): void
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $quantitySalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantitySalesUnit($quantitySalesUnitTransfer);

        //Act
        $salesOrderItemEntity = $this->quantitySalesUnitOrderItemExpanderPreSavePlugin->expandOrderItem(
            new QuoteTransfer(),
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer()
        );

        //Assert
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getQuantityMeasurementUnitName());
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getQuantityBaseMeasurementUnitName());
        $this->assertSame($quantitySalesUnitTransfer->getPrecision(), $salesOrderItemEntity->getQuantityMeasurementUnitPrecision());
        $this->assertSame($quantitySalesUnitTransfer->getConversion(), $salesOrderItemEntity->getQuantityMeasurementUnitConversion());
    }
}
