<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use SprykerTest\Zed\ProductCategory\PageObject\ProductCategoryAssignPage;

class AssignHelper extends Module
{
    /**
     * @param \Codeception\TestInterface $test
     * @param \Exception $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail): void
    {
        parent::_failed($test, $fail);

        $this->removeRelations();
    }

    /**
     * @return void
     */
    protected function removeRelations(): void
    {
        $idCategory = ProductCategoryAssignPage::CATEGORY[ProductCategoryAssignPage::CATEGORY_ID];
        $query = new SpyProductCategoryQuery();
        $query
            ->findByFkCategory($idCategory)
            ->delete();
    }

    /**
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function assignProductToCategory(int $idCategory, int $idProductAbstract): void
    {
        $spyProductCategory = new SpyProductCategory();
        $spyProductCategory
            ->setFkCategory($idCategory)
            ->setFkProductAbstract($idProductAbstract)
            ->save();
    }
}
