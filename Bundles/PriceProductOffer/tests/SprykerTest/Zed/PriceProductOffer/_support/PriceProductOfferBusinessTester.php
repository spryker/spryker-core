<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOffer;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;

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
class PriceProductOfferBusinessTester extends Actor
{
    use _generated\PriceProductOfferBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param string $sku
     *
     * @return void
     */
    public function createPriceProductOfferPriceForSku(string $sku): void
    {
        $storeTransfer = $this->haveStore();
        $idCurrency = $this->haveCurrency();
        $priceTypeTransfer = $this->havePriceType();
        $productOfferTransfer = $this->haveProductOffer([
           ProductOfferTransfer::CONCRETE_SKU => $sku,
        ]);

        (new SpyPriceProductOffer())
           ->setFkProductOffer($productOfferTransfer->getIdProductOffer())
           ->setFkStore($storeTransfer->getIdStore())
           ->setFkCurrency($idCurrency)
           ->setNetPrice(5)
           ->setGrossPrice(4)
           ->setFkPriceType($priceTypeTransfer->getIdPriceType())
           ->save();
    }
}
