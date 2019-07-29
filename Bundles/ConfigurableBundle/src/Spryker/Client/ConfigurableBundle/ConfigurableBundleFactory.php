<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle;

use Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface;
use Spryker\Client\ConfigurableBundle\Zed\ConfigurableBundleStub;
use Spryker\Client\ConfigurableBundle\Zed\ConfigurableBundleStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ConfigurableBundle\Zed\ConfigurableBundleStubInterface
     */
    public function createZedConfigurableBundleStub(): ConfigurableBundleStubInterface
    {
        return new ConfigurableBundleStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface
     */
    public function getZedRequestClient(): ConfigurableBundleToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
