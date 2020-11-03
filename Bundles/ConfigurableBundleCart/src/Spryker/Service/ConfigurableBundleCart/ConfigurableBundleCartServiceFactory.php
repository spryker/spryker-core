<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ConfigurableBundleCart;

use Spryker\Service\ConfigurableBundleCart\Expander\ConfiguredBundleGroupKeyExpander;
use Spryker\Service\ConfigurableBundleCart\Expander\ConfiguredBundleGroupKeyExpanderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class ConfigurableBundleCartServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ConfigurableBundleCart\Expander\ConfiguredBundleGroupKeyExpanderInterface
     */
    public function createConfiguredBundleGroupKeyExpander(): ConfiguredBundleGroupKeyExpanderInterface
    {
        return new ConfiguredBundleGroupKeyExpander();
    }
}
