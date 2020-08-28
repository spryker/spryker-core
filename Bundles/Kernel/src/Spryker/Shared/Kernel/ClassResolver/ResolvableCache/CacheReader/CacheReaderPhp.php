<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ResolvableCache\CacheReader;

use Spryker\Shared\Kernel\KernelConfig;

class CacheReaderPhp implements CacheReaderInterface
{
    /***
     * @var \Spryker\Shared\Kernel\KernelConfig
     */
    protected $config;

    /**
     * @param \Spryker\Shared\Kernel\KernelConfig $config
     */
    public function __construct(KernelConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string[]
     */
    public function read(): array
    {
        if (!file_exists($this->config->getResolvableCacheFilePath())) {
            trigger_error(sprintf('The resolvable class cache is enabled but was not generated.'), E_USER_DEPRECATED);

            return [];
        }

        return include_once($this->config->getResolvableCacheFilePath());
    }
}
