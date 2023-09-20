<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandSalesOrderItemWithAmountAndAmountSkuTest
 * Add your own group annotations below this line
 */
class ExpandSalesOrderItemWithAmountAndAmountSkuTest extends Unit
{
    /**
     * @var string
     */
    protected const BOX_PACKAGING_TYPE = 'box';

    /**
     * @var int
     */
    protected const ITEM_QUANTITY = 2;

    /**
     * @var int
     */
    protected const PACKAGE_AMOUNT = 4;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandOrderItemWithAmountAndAmountSku(): void
    {
        // Arrange
        $itemTransfer = $this->createTestPackagingUnitItemTransfer();

        // Act
        $salesOrderItemEntity = $this->tester->getFacade()->expandSalesOrderItemWithAmountAndAmountSku(
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer(),
        );

        // Assert
        $this->assertSame($itemTransfer->getAmount()->toString(), $salesOrderItemEntity->getAmount()->toString());
        $this->assertSame($itemTransfer->getAmountLeadProduct()->getSku(), $salesOrderItemEntity->getAmountSku());
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createTestPackagingUnitItemTransfer(): ItemTransfer
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::BOX_PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        return (new ItemTransfer())
            ->setQuantity(static::ITEM_QUANTITY)
            ->setId($boxProductConcreteTransfer->getIdProductConcrete())
            ->setSku($boxProductConcreteTransfer->getSku())
            ->setAmount(static::PACKAGE_AMOUNT)
            ->setAmountLeadProduct($itemProductConcreteTransfer);
    }
}
