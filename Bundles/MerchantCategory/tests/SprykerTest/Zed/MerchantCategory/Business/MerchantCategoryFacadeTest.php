<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategory\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCategory
 * @group Business
 * @group Facade
 * @group MerchantCategoryFacadeTest
 * Add your own group annotations below this line
 */
class MerchantCategoryFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantCategory\MerchantCategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetReturnsListOfMerchantCategoriesByMerchantId(): void
    {
        $merchantCategoryTransfer = $this->tester->haveMerchantCategory();

        $merchantCategoryResponseTransfer = $this->tester->getFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantCategoryTransfer->getFkMerchant())
            );

        $this->assertCount(1, $merchantCategoryResponseTransfer->getCategories());
    }

    /**
     * @return void
     */
    public function testGetReturnsNothingForNotExistingCategory(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantCategoryResponseTransfer = $this->tester->getFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantTransfer->getIdMerchant())
            );

        $this->assertEmpty($merchantCategoryResponseTransfer->getCategories());
    }
}
