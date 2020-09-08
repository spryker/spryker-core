<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Remover;

use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreInterface;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig;

/**
 * @deprecated Use {@link \Spryker\Zed\RestRequestValidator\Business\Remover\RestRequestValidatorCodeBucketCacheRemover} instead.
 */
class RestRequestValidatorCacheRemover implements RestRequestValidatorCacheRemoverInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreInterface
     */
    protected $store;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    protected $filesystem;

    /**
     * @var \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreInterface $store
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface $filesystem
     * @param \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorToStoreInterface $store,
        RestRequestValidatorToFilesystemAdapterInterface $filesystem,
        RestRequestValidatorConfig $config
    ) {
        $this->store = $store;
        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        foreach ($this->store->getAllowedStores() as $storeName) {
            $outdatedConfigFiles = $this->getOutdatedConfig($storeName);
            if (!empty($outdatedConfigFiles)) {
                $this->filesystem->remove($outdatedConfigFiles);
            }
        }
    }

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    protected function getOutdatedConfig(string $storeName): array
    {
        return glob(sprintf($this->config->getCacheFilePathPattern(), $storeName), GLOB_NOSORT) ?: [];
    }
}
