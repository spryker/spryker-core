<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductVolume;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractor;
use Spryker\Client\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface;
use Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;

class PriceProductVolumeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function createVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return new VolumePriceExtractor(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
