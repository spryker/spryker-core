<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business;

use Spryker\Zed\ConfigurableBundleCart\Business\Checker\ConfiguredBundleQuantityChecker;
use Spryker\Zed\ConfigurableBundleCart\Business\Checker\ConfiguredBundleQuantityCheckerInterface;
use Spryker\Zed\ConfigurableBundleCart\Business\Updater\ConfiguredBundleQuantityUpdater;
use Spryker\Zed\ConfigurableBundleCart\Business\Updater\ConfiguredBundleQuantityUpdaterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\ConfigurableBundleCartConfig getConfig()
 */
class ConfigurableBundleCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundleCart\Business\Updater\ConfiguredBundleQuantityUpdaterInterface
     */
    public function createConfiguredBundleQuantityUpdater(): ConfiguredBundleQuantityUpdaterInterface
    {
        return new ConfiguredBundleQuantityUpdater();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleCart\Business\Checker\ConfiguredBundleQuantityCheckerInterface
     */
    public function createConfiguredBundleQuantityChecker(): ConfiguredBundleQuantityCheckerInterface
    {
        return new ConfiguredBundleQuantityChecker();
    }
}
