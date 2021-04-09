<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
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
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getRootCategoryNode(): NodeTransfer
    {
        $categoryNodeEntity = SpyCategoryNodeQuery::create()
            ->filterByIsRoot(true)
            ->findOne();

        return (new NodeTransfer())->fromArray($categoryNodeEntity->toArray(), true);
    }

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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string $storeName
     * @param array $storageData
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage
     */
    public function haveProductAbstractCategoryStorageEntity(
        ProductConcreteTransfer $productConcreteTransfer,
        string $storeName,
        array $storageData = []
    ): SpyProductAbstractCategoryStorage {
        $productAbstractCategoryStorageEntity = $this->createProductAbstractCategoryStorageQuery()
            ->filterByFkProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->filterByStore($storeName)
            ->findOneOrCreate();

        if (!$productAbstractCategoryStorageEntity->isNew()) {
            return $productAbstractCategoryStorageEntity;
        }

        $productAbstractCategoryStorageEntity->setLocale(
            $productConcreteTransfer->getLocalizedAttributes()->offsetGet(0)->getLocale()->getLocaleName()
        );

        $productAbstractCategoryStorageEntity->setData(
            $this->getLocator()->utilEncoding()->service()->encodeJson($storageData)
        );

        $productAbstractCategoryStorageEntity->save();

        return $productAbstractCategoryStorageEntity;
    }

    /**
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    protected function createProductAbstractCategoryStorageQuery(): SpyProductAbstractCategoryStorageQuery
    {
        return SpyProductAbstractCategoryStorageQuery::create();
    }
}
