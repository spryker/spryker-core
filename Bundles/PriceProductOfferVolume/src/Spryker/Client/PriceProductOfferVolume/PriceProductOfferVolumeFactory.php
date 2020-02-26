<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor\VolumePriceExtractor;
use Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor\VolumePriceExtractorInterface;

class PriceProductOfferVolumeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function createOfferVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return new VolumePriceExtractor();
    }
}
