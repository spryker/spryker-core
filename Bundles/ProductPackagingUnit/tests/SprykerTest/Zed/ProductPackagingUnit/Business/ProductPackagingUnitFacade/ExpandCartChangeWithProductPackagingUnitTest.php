<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandCartChangeWithProductPackagingUnitTest
 * Add your own group annotations below this line
 */
class ExpandCartChangeWithProductPackagingUnitTest extends Unit
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
    public function testExpandCartChangeWithProductPackagingUnit(): void
    {
        // Arrange
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct();

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::BOX_PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $cartChange = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setId($boxProductConcreteTransfer->getIdProductConcrete())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(static::ITEM_QUANTITY)
                    ->setAmount(static::PACKAGE_AMOUNT),
            );

        // Act
        $this->tester->getFacade()->expandCartChangeWithProductPackagingUnit($cartChange);

        // Assert
        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $itemTransfer->getAmountLeadProduct());
            $this->assertSame($itemProductConcreteTransfer->getSku(), $itemTransfer->getAmountLeadProduct()->getSku());
        }
    }
}
