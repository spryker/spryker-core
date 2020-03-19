<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferValidity;

use Codeception\Actor;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery;

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
 * @method \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferValidityBusinessTester extends Actor
{
    use _generated\ProductOfferValidityBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return void
     */
    public function truncateProductOfferValidities(): void
    {
        $this->truncateTableRelations($this->getProductOfferValidityPropelQuery());
    }

    /**
     * @return \Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery
     */
    public function getProductOfferValidityPropelQuery(): SpyProductOfferValidityQuery
    {
        return SpyProductOfferValidityQuery::create();
    }
}
