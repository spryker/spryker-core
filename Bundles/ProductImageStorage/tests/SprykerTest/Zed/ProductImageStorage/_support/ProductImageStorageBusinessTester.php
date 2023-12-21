<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductAbstractImageStorageBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;

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
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductImageStorage\PHPMD)
 */
class ProductImageStorageBusinessTester extends Actor
{
    use _generated\ProductImageStorageBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer
     */
    public function haveProductAbstractImageStorage(LocaleTransfer $localeTransfer, array $seedData = []): ProductAbstractImageStorageTransfer
    {
        $productAbstractImageStorageTransfer = (new ProductAbstractImageStorageBuilder($seedData))->build();

        $productAbstractImageStorageEntity = new SpyProductAbstractImageStorage();
        $productAbstractImageStorageEntity->setFkProductAbstract($productAbstractImageStorageTransfer->getIdProductAbstractOrFail());
        $productAbstractImageStorageEntity->setLocale($localeTransfer->getLocaleNameOrFail());
        $productAbstractImageStorageEntity->setData($productAbstractImageStorageTransfer->toArray());
        $productAbstractImageStorageEntity->save();

        return $productAbstractImageStorageTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return list<\Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage>
     */
    public function findProductAbstractImageStorageCollectionByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getProductAbstractImageStorageQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->find()
            ->getData();
    }

    /**
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery
     */
    protected function getProductAbstractImageStorageQuery(): SpyProductAbstractImageStorageQuery
    {
        return SpyProductAbstractImageStorageQuery::create();
    }
}
