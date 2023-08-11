<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategory;

use Codeception\Actor;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantCategoryTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantCategory\PHPMD)
 */
class MerchantCategoryBusinessTester extends Actor
{
    use _generated\MerchantCategoryBusinessTesterActions;

    /**
     * @return void
     */
    public function cleanUpDatabase(): void
    {
        $this->cleanUpMerchantCategoryTable();
    }

    /**
     * @return void
     */
    protected function cleanUpMerchantCategoryTable(): void
    {
        SpyMerchantCategoryQuery::create()->deleteAll();
    }

    /**
     * @param int $categoriesCount
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchantWithCategories(int $categoriesCount = 1): MerchantTransfer
    {
        $merchantTransfer = $this->haveMerchant();

        for ($i = 0; $i < $categoriesCount; $i++) {
            $categoryTransfer = $this->createCategory('TEST_CATEGORY_' . microtime());
            $this->haveMerchantCategory([
                MerchantCategoryTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
                MerchantCategoryTransfer::FK_CATEGORY => $categoryTransfer->getIdCategory(),
            ]);
            $merchantTransfer->addCategory($categoryTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param int $expectedCategoriesCount
     * @param \Generated\Shared\Transfer\MerchantTransfer $actualMerchantTransfer
     *
     * @return void
     */
    public function assertMerchantHasCategoriesCount(int $expectedCategoriesCount, MerchantTransfer $actualMerchantTransfer): void
    {
        $categories = $actualMerchantTransfer->getCategories()->getArrayCopy();

        $this->assertCount($expectedCategoriesCount, $categories);

        if ($expectedCategoriesCount > 0) {
            $this->assertContainsOnlyInstancesOf(CategoryTransfer::class, $categories);
        }
    }
}
