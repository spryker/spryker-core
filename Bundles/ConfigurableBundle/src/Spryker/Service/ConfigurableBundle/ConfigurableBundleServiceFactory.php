<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ConfigurableBundle;

use Spryker\Service\ConfigurableBundle\Expander\ConfiguredBundleGroupKeyExpander;
use Spryker\Service\ConfigurableBundle\Expander\ConfiguredBundleGroupKeyExpanderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class ConfigurableBundleServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ConfigurableBundle\Expander\ConfiguredBundleGroupKeyExpanderInterface
     */
    public function createConfiguredBundleGroupKeyExpander(): ConfiguredBundleGroupKeyExpanderInterface
    {
        return new ConfiguredBundleGroupKeyExpander();
    }
}
