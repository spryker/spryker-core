<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Zed\Kernel\ClassResolver\Config\SharedConfigResolver;

trait BundleConfigResolverAwareTrait
{

    /**
     * @var \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @var \Spryker\Shared\Kernel\AbstractSharedConfig
     */
    private $sharedConfig;

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleConfig $config
     *
     * @return $this
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @TODO this method should not be public @see spryker/spryker#940
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractSharedConfig $sharedConfig
     *
     * @return $this
     */
    public function setSharedConfig(AbstractSharedConfig $sharedConfig)
    {
        $this->sharedConfig = $sharedConfig;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig|\Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected function getSharedConfig()
    {
        if ($this->sharedConfig === null) {
            $this->sharedConfig = $this->resolveSharedConfig();
        }

        return $this->sharedConfig;
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
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

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    private function resolveSharedConfig()
    {
        $resolver = new SharedConfigResolver();
        $sharedConfig = $resolver->resolve($this);

        return $sharedConfig;
    }

}
