<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfigurationStorage;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductConfigurationStorage\Filter\PriceProductConfigurationFilter;
use Spryker\Service\ProductConfigurationStorage\Filter\PriceProductConfigurationFilterInterface;
use Spryker\Service\ProductConfigurationStorage\Filter\PriceProductConfigurationVolumeFilter;
use Spryker\Service\ProductConfigurationStorage\Filter\PriceProductConfigurationVolumeFilterInterface;

class ProductConfigurationStorageServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductConfigurationStorage\Filter\PriceProductConfigurationFilterInterface
     */
    public function createPriceProductConfigurationFilter(): PriceProductConfigurationFilterInterface
    {
        return new PriceProductConfigurationFilter();
    }

    /**
     * @return \Spryker\Service\ProductConfigurationStorage\Filter\PriceProductConfigurationVolumeFilterInterface
     */
    public function createVolumePriceProductConfigurationFilter(): PriceProductConfigurationVolumeFilterInterface
    {
        return new PriceProductConfigurationVolumeFilter();
    }
}
