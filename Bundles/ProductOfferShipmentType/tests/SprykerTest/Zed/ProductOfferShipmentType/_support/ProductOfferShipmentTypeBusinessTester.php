<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType;

use Codeception\Actor;
use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Propel\Runtime\Collection\Collection;

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
 * @method \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferShipmentTypeBusinessTester extends Actor
{
    use _generated\ProductOfferShipmentTypeBusinessTesterActions;

    /**
     * @param string $productOfferReference
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentType>
     */
    public function getProductOfferShipmentTypeEntitiesByProductOfferReference(string $productOfferReference): Collection
    {
        return $this->getProductOfferShipmentTypeQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->find();
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    public function getProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }
}
