<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilQuantityServiceInterface;

class PriceProductVolumeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilQuantityServiceInterface
     */
    public function getUtilQuantityService(): PriceProductVolumeToUtilQuantityServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::SERVICE_UTIL_QUANTITY);
    }
}
