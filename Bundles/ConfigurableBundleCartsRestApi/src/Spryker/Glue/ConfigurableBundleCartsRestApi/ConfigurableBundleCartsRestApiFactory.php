<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi;

use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Adder\ConfiguredBundleAdder;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Adder\ConfiguredBundleAdderInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Deleter\ConfiguredBundleDeleter;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Deleter\ConfiguredBundleDeleterInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Updater\ConfiguredBundleUpdater;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Updater\ConfiguredBundleUpdaterInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig getConfig()
 */
class ConfigurableBundleCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Adder\ConfiguredBundleAdderInterface
     */
    public function createConfiguredBundleAdder(): ConfiguredBundleAdderInterface
    {
        return new ConfiguredBundleAdder();
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Updater\ConfiguredBundleUpdaterInterface
     */
    public function createConfiguredBundleUpdater(): ConfiguredBundleUpdaterInterface
    {
        return new ConfiguredBundleUpdater();
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Deleter\ConfiguredBundleDeleterInterface
     */
    public function createConfiguredBundleDeleter(): ConfiguredBundleDeleterInterface
    {
        return new ConfiguredBundleDeleter();
    }
}
