<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;
use Spryker\Service\PriceProductVolume\VolumePriceReader\VolumePriceReader;
use Spryker\Service\PriceProductVolume\VolumePriceReader\VolumePriceReaderInterface;
use Spryker\Service\PriceProductVolume\VolumePriceUpdater\VolumePriceUpdater;
use Spryker\Service\PriceProductVolume\VolumePriceUpdater\VolumePriceUpdaterInterface;

class PriceProductVolumeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProductVolume\VolumePriceUpdater\VolumePriceUpdaterInterface
     */
    public function createVolumePriceUpdater(): VolumePriceUpdaterInterface
    {
        return new VolumePriceUpdater($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Service\PriceProductVolume\VolumePriceReader\VolumePriceReaderInterface
     */
    public function createVolumePriceReader(): VolumePriceReaderInterface
    {
        return new VolumePriceReader($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
