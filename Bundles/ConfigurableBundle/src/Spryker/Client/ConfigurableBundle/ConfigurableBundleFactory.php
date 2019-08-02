<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle;

use Spryker\Client\ConfigurableBundle\Calculation\ConfiguredBundlePriceCalculation;
use Spryker\Client\ConfigurableBundle\Calculation\ConfiguredBundlePriceCalculationInterface;
use Spryker\Client\ConfigurableBundle\Reader\ConfiguredBundleReader;
use Spryker\Client\ConfigurableBundle\Reader\ConfiguredBundleReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ConfigurableBundle\Reader\ConfiguredBundleReaderInterface
     */
    public function createConfiguredBundleReader(): ConfiguredBundleReaderInterface
    {
        return new ConfiguredBundleReader(
            $this->createConfiguredBundlePriceCalculation()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundle\Calculation\ConfiguredBundlePriceCalculationInterface
     */
    public function createConfiguredBundlePriceCalculation(): ConfiguredBundlePriceCalculationInterface
    {
        return new ConfiguredBundlePriceCalculation();
    }
}
