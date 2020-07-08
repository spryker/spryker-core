<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProduct
 * @group Business
 * @group Facade
 * @group MerchantProductFacadeTest
 *
 * Add your own group annotations below this line
 */
class MerchantProductFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProduct\MerchantProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindMerchantReturnsMerchant(): void
    {
        // Arrange
        $expectedMerchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->addMerchantProductRelation($expectedMerchantTransfer->getIdMerchant(), $productConcreteTransfer->getFkProductAbstract());

        // Act
        $merchantTransfer = $this->tester->getFacade()->findMerchant(
            (new MerchantProductCriteriaTransfer())->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
        );

        // Assert
        $this->assertEquals($expectedMerchantTransfer->getIdMerchant(), $merchantTransfer->getIdMerchant());
        $this->assertEquals($expectedMerchantTransfer->getName(), $merchantTransfer->getName());
    }

    /**
     * @return void
     */
    public function testFindMerchantForNotExistingMerchantProductReturnsNull(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductAbstractTableIsEmpty();

        // Act
        $merchantTransfer = $this->tester->getFacade()->findMerchant(
            (new MerchantProductCriteriaTransfer())->setIdProductAbstract(1)
        );

        // Assert
        $this->assertNull($merchantTransfer);
    }
}
