<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOffer;

use Codeception\Actor;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;

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
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductOfferBusinessTester extends Actor
{
    use _generated\PriceProductOfferBusinessTesterActions;

    /**
     * @return void
     */
    public function ensurePriceProductOfferTableIsEmpty(): void
    {
        $priceProductOfferQuery = $this->getPriceProductOfferPropelQuery();
        $this->ensureDatabaseTableIsEmpty($priceProductOfferQuery);
        $priceProductOfferQuery->deleteAll();
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    public function getPriceProductOfferByIdProductOffer(int $idProductOffer): PriceProductOfferTransfer
    {
        $priceProductOfferEntity = $this->getPriceProductOfferPropelQuery()
           ->filterByFkProductOffer($idProductOffer)
           ->orderByFkPriceProductStore()
           ->findOne();

        $priceProductOfferTransfer = (new PriceProductOfferTransfer())->fromArray($priceProductOfferEntity->toArray(), true);

        return $priceProductOfferTransfer;
    }

    /**
     * @return \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery
     */
    protected function getPriceProductOfferPropelQuery(): SpyPriceProductOfferQuery
    {
        return SpyPriceProductOfferQuery::create();
    }
}
