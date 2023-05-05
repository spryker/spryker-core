<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use SprykerTest\Zed\MerchantProduct\MerchantProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProduct
 * @group Business
 * @group Facade
 * @group GetMerchantProductAbstractCollectionTest
 *
 * Add your own group annotations below this line
 */
class GetMerchantProductAbstractCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProduct\MerchantProductBusinessTester
     */
    protected MerchantProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetMerchantProductAbstractCollectionReturnsCollectionWithFiveAbstractProductsWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 15; $i++) {
            $merchantTransfer = $this->tester->haveMerchant();
            $productAbstractTransfer = $this->tester->haveProductAbstract();
            $this->tester->haveMerchantProduct([
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ]);
        }
        $merchantProductAbstractCriteriaTransfer = (new MerchantProductAbstractCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(5)->setOffset(10),
            );

        // Act
        $merchantProductAbstractCollectionTransfer = $this->tester->getFacade()
            ->getMerchantProductAbstractCollection($merchantProductAbstractCriteriaTransfer);

        // Assert
        $this->assertCount(5, $merchantProductAbstractCollectionTransfer->getMerchantProductAbstracts());
    }
}
