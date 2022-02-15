<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter;

use Spryker\Zed\Kernel\KernelConfig;

class CacheWriterPhp implements CacheWriterInterface
{
    /**
     * @var \Spryker\Zed\Kernel\KernelConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Kernel\KernelConfig $config
     */
    public function __construct(KernelConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array<string> $cache
     * @param string $cacheFileNamePostfix
     *
     * @return void
     */
    public function write(array $cache, string $cacheFileNamePostfix): void
    {
        $resolvableCacheFilePathPattern = $this->config->getResolvableCacheFilePathPattern();

        $resolvableCacheFilePath = sprintf($resolvableCacheFilePathPattern, $cacheFileNamePostfix);

        if (!is_dir(dirname($resolvableCacheFilePath))) {
            mkdir(dirname($resolvableCacheFilePath), $this->config->getPermissionMode(), true);
        }

        $fileContent = sprintf('<?php return %s;', var_export($cache, true));

        file_put_contents($resolvableCacheFilePath, $fileContent);
    }
}
