<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel;

use Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock;

trait BundleConfigResolverAwareTrait
{
    /**
     * @var \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @param \Spryker\Glue\Kernel\AbstractBundleConfig $config
     *
     * @return $this
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        $resolver = new BundleConfigResolver();
        $config = $resolver->resolve($this);

        $bundleConfigMock = new BundleConfigMock();
        if ($bundleConfigMock->hasBundleConfigMock($config)) {
            return $bundleConfigMock->getBundleConfigMock($config);
        }

        return $config;
    }
}
