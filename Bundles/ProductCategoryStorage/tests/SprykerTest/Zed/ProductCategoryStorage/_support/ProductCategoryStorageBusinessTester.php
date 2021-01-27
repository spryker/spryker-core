<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductCategoryStorageBusinessTester extends Actor
{
    use _generated\ProductCategoryStorageBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage[]
     */
    public function getProductAbstractCategoryStorageEntities(ProductConcreteTransfer $productConcreteTransfer): ObjectCollection
    {
        return $this->createProductAbstractCategoryStorageQuery()
            ->filterByFkProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->find();
    }

    /**
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    protected function createProductAbstractCategoryStorageQuery(): SpyProductAbstractCategoryStorageQuery
    {
        return SpyProductAbstractCategoryStorageQuery::create();
    }
}
