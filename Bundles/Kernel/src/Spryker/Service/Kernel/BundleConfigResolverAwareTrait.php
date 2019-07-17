<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Service\Kernel\ClassResolver\Config\BundleConfigResolver;

trait BundleConfigResolverAwareTrait
{
    /**
     * @var \Spryker\Service\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @param \Spryker\Service\Kernel\AbstractBundleConfig $config
     *
     * @return $this
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractBundleConfig
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        $resolver = new BundleConfigResolver();

        return $resolver->resolve($this);
    }
}
