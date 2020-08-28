<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductOfferVolume;

use Codeception\Actor;
use Generated\Shared\Transfer\PriceProductTransfer;

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
 * @method \Spryker\Client\PriceProductOfferVolume\PriceProductOfferVolumeClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductOfferVolumeTester extends Actor
{
    use _generated\PriceProductOfferVolumeTesterActions;

    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function preparePriceProductsWithVolumePrices(): array
    {
        $priceProductTransfer = $this->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'test']);
        $priceProductTransfer->getMoneyValue()->setPriceData(static::PRICE_DATA_VOLUME);

        return [$priceProductTransfer];
    }
}
