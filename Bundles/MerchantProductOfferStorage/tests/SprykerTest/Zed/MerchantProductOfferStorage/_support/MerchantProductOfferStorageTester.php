<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage;

use Codeception\Actor;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
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
class MerchantProductOfferStorageTester extends Actor
{
    use _generated\MerchantProductOfferStorageTesterActions;

    /**
     * @param string $productOfferReference
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getProductOfferEntities(string $productOfferReference): ObjectCollection
    {
        return SpyProductOfferStorageQuery::create()->findByProductOfferReference($productOfferReference);
    }

    /**
     * @param string $concreteSku
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getProductConcreteProductOffersEntities(string $concreteSku): ObjectCollection
    {
        return SpyProductConcreteProductOffersStorageQuery::create()->findByConcreteSku($concreteSku);
    }
}
