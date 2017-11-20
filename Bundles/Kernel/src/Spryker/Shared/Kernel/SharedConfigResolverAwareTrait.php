<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\ClassResolver\Config\SharedConfigResolver;

trait SharedConfigResolverAwareTrait
{
    /**
     * @var \Spryker\Shared\Kernel\AbstractSharedConfig
     */
    private $sharedConfig;

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
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig
     */
    protected function getSharedConfig()
    {
        if ($this->sharedConfig === null) {
            $this->sharedConfig = $this->resolveSharedConfig();
        }

        return $this->sharedConfig;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig
     */
    private function resolveSharedConfig()
    {
        $resolver = new SharedConfigResolver();
        $sharedConfig = $resolver->resolve($this);

        return $sharedConfig;
    }
}
