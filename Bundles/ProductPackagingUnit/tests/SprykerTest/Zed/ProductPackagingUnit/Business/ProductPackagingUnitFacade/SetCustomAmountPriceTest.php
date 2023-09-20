<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
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
 * @group SetCustomAmountPriceTest
 * Add your own group annotations below this line
 */
class SetCustomAmountPriceTest extends Unit
{
    /**
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var int
     */
    protected const PACKAGE_AMOUNT = 4;

    /**
     * @var string
     */
    protected const BOX_PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testSetCustomAmountPrice(): void
    {
        // Arrange
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

        $unitGrossPrice = 6000;

        $productPackagingUnitAmountTransfer = (new ProductPackagingUnitAmountTransfer())
            ->setIsAmountVariable(true)
            ->setDefaultAmount(4);

        $productPackagingUnitTransfer = (new ProductPackagingUnitTransfer())
            ->setProductPackagingUnitAmount($productPackagingUnitAmountTransfer);

        $cartChange = (new CartChangeTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setPriceMode(static::PRICE_MODE_GROSS),
            )
            ->addItem(
                (new ItemTransfer())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(1)
                    ->setAmount(6)
                    ->setProductPackagingUnit($productPackagingUnitTransfer)
                    ->setUnitGrossPrice($unitGrossPrice),
            );

        // Act
        $this->tester->getFacade()->setCustomAmountPrice($cartChange);

        // Assert
        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertNotEquals($itemTransfer->getUnitGrossPrice(), $unitGrossPrice);
            $this->assertSame(9000, $itemTransfer->getUnitGrossPrice());
        }
    }
}
