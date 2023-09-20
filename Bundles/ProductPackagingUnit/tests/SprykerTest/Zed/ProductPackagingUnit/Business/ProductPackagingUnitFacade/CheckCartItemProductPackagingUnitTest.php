<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
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
 * @group CheckCartItemProductPackagingUnitTest
 * Add your own group annotations below this line
 */
class CheckCartItemProductPackagingUnitTest extends Unit
{
    /**
     * @var string
     */
    protected const BOX_PACKAGING_TYPE = 'box';

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
    public function testCheckCartItemProductPackagingUnitWillReturnSuccessfulResponseWithEmptyCartChangeTransfer(): void
    {
        // Arrange
        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkCartItemProductPackagingUnit($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckCartItemProductPackagingUnitWillReturnSuccessfulResponseWithExistingPackagingUnitForCartItem(): void
    {
        // Arrange
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::BOX_PACKAGING_TYPE]);

        $spyProductPackagingUnitEntityTransfer = $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $cartChangeTransfer = $this->tester->addProductPackagingUnitToCartChangeTransfer(
            $this->tester->createEmptyCartChangeTransfer(),
            $boxProductConcreteTransfer->getSku(),
            $spyProductPackagingUnitEntityTransfer->getIdProductPackagingUnit(),
        );

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkCartItemProductPackagingUnit($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckCartItemProductPackagingUnitWillReturnResponseWithErrorWithNoPackagingUnit(): void
    {
        // Arrange
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem($boxProductConcreteTransfer->getSku(), 0);

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkCartItemProductPackagingUnit($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }
}
