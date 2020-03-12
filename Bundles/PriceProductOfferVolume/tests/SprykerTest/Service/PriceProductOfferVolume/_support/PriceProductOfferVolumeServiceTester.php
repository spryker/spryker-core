<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductOfferVolume;

use Codeception\Actor;
use Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface;

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
class PriceProductOfferVolumeServiceTester extends Actor
{
    use _generated\PriceProductOfferVolumeServiceTesterActions;

    /**
     * @return \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface
     */
    public function getPriceProductOfferVolumeService(): PriceProductOfferVolumeServiceInterface
    {
        return $this->getLocator()->priceProductOfferVolume()->service();
    }
}
