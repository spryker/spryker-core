<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch;

use Codeception\Actor;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductSearchBusinessTester extends Actor
{
    use _generated\ProductSearchBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductAttributeKeyTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->createProductAttributeKeyQuery());
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function createProductAttributeKeyQuery(): SpyProductAttributeKeyQuery
    {
        return SpyProductAttributeKeyQuery::create();
    }
}
