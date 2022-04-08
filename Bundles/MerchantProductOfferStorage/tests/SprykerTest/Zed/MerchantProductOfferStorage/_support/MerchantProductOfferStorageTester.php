<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductOfferStorageTester extends Actor
{
    use _generated\MerchantProductOfferStorageTesterActions;

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
     * @param string $productSku
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage>
     */
    public function getProductConcreteProductOffersEntities(string $productSku): ObjectCollection
    {
        return SpyProductConcreteProductOffersStorageQuery::create()->findByConcreteSku($productSku);
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage>
     */
    public function getProductOfferEntities(string $productOfferReference): ObjectCollection
    {
        return SpyProductOfferStorageQuery::create()->findByProductOfferReference($productOfferReference);
    }
}
