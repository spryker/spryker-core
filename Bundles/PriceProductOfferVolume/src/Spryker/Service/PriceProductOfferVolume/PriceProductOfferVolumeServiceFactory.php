<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferVolume;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProductOfferVolume\PriceProduct\PriceProductReader;
use Spryker\Service\PriceProductOfferVolume\PriceProduct\PriceProductReaderInterface;

class PriceProductOfferVolumeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProductOfferVolume\PriceProduct\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader();
    }
}
