<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryMerchantCommissionConnector;

use Codeception\Actor;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\CategoryMerchantCommissionConnector\Business\CategoryMerchantCommissionConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CategoryMerchantCommissionConnectorBusinessTester extends Actor
{
    use _generated\CategoryMerchantCommissionConnectorBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductCategoryTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductCategoryQuery());
    }

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    protected function getProductCategoryQuery(): SpyProductCategoryQuery
    {
        return SpyProductCategoryQuery::create();
    }
}
