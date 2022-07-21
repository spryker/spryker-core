<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOfferGui;

use Codeception\Actor;
use Generated\Shared\Transfer\PriceProductTransfer;

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
class PriceProductOfferGuiCommunicationTester extends Actor
{
    use _generated\PriceProductOfferGuiCommunicationTesterActions;

    /**
     * @uses \Spryker\Shared\PriceProductOffer\PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER
     *
     * @var string
     */
    protected const DIMENSION_TYPE_PRODUCT_OFFER = 'PRODUCT_OFFER';

    /**
     * @param array $priceProductOverride
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductWithOffer(array $priceProductOverride = []): PriceProductTransfer
    {
        $priceProductTransfer = $this->havePriceProduct($priceProductOverride);
        $productOfferTransfer = $this->haveProductOffer();

        $priceProductTransfer->getPriceDimension()
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setType(static::DIMENSION_TYPE_PRODUCT_OFFER);

        return $priceProductTransfer;
    }
}
