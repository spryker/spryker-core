<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferVolume;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface;
use Spryker\Service\PriceProductOfferVolume\PriceProduct\PriceProductReader;
use Spryker\Service\PriceProductOfferVolume\PriceProduct\PriceProductReaderInterface;
use Spryker\Service\PriceProductOfferVolume\VolumePriceExtractor\ProductOfferVolumePriceExtractor;
use Spryker\Service\PriceProductOfferVolume\VolumePriceExtractor\ProductOfferVolumePriceExtractorInterface;

class PriceProductOfferVolumeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProductOfferVolume\PriceProduct\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader();
    }

    /**
     * @return \Spryker\Service\PriceProductOfferVolume\VolumePriceExtractor\ProductOfferVolumePriceExtractorInterface
     */
    public function createProductOfferVolumePriceExtractor(): ProductOfferVolumePriceExtractorInterface
    {
        return new ProductOfferVolumePriceExtractor($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Service\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductOfferVolumeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductOfferVolumeDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
