<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStorage;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferStorageTester extends Actor
{
    use _generated\ProductOfferStorageTesterActions;

    /**
     * @return void
     */
    public function clearProductOfferData(): void
    {
        SpyProductOfferStoreQuery::create()->deleteAll();
        SpyProductOfferStorageQuery::create()->deleteAll();
        SpyProductConcreteProductOffersStorageQuery::create()->deleteAll();
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorage>
     */
    public function getProductOfferStorageEntities(array $productOfferReferences = []): ObjectCollection
    {
        return SpyProductOfferStorageQuery::create()
            ->filterByProductOfferReference_In($productOfferReferences)
            ->find();
    }

    /**
     * @param array<string> $productSkus
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage>
     */
    public function getProductConcreteProductOffersEntities(array $productSkus = []): ObjectCollection
    {
        return SpyProductConcreteProductOffersStorageQuery::create()
            ->filterByConcreteSku_In($productSkus)
            ->find();
    }
}
