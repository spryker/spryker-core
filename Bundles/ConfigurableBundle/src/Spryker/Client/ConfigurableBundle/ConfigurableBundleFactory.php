<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle;

use Spryker\Client\ConfigurableBundle\Calculator\ConfiguredBundlePriceCalculator;
use Spryker\Client\ConfigurableBundle\Calculator\ConfiguredBundlePriceCalculatorInterface;
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
            $this->createConfiguredBundlePriceCalculator()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundle\Calculator\ConfiguredBundlePriceCalculatorInterface
     */
    public function createConfiguredBundlePriceCalculator(): ConfiguredBundlePriceCalculatorInterface
    {
        return new ConfiguredBundlePriceCalculator();
    }
}
