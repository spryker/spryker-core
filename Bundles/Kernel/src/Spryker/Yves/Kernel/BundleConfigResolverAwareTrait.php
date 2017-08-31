<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Zed\Kernel\ClassResolver\Config\SharedConfigResolver;

trait BundleConfigResolverAwareTrait
{

    /**
     * @var \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleConfig $config
     *
     * @return $this
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
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
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
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
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        $resolver = new BundleConfigResolver();

        return $resolver->resolve($this);
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
