<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface;
use Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor\ProductOfferVolumePriceExtractor;
use Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor\ProductOfferVolumePriceExtractorInterface;

class PriceProductOfferVolumeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor\ProductOfferVolumePriceExtractorInterface
     */
    public function createProductOfferVolumePriceExtractor(): ProductOfferVolumePriceExtractorInterface
    {
        return new ProductOfferVolumePriceExtractor($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Client\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductOfferVolumeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductOfferVolumeDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
