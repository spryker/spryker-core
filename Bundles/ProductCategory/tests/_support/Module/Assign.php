<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ProductCategory\Module;

use Acceptance\ProductCategory\ProductCategory\Zed\PageObject\ProductCategoryAssignPage;
use Codeception\Module;
use Codeception\TestInterface;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;

class Assign extends Module
{

    /**
     * @param \Codeception\TestInterface $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail)
    {
        parent::_failed($test, $fail);

        $this->removeRelations();
    }

    /**
     * @return void
     */
    protected function removeRelations()
    {
        $idCategory = ProductCategoryAssignPage::CATEGORY[ProductCategoryAssignPage::CATEGORY_ID];
        $query = new SpyProductCategoryQuery();
        $query
            ->findByFkCategory($idCategory)
            ->delete();
    }

}
