<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Saver;

use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig;

class RestRequestValidatorCacheSaver implements RestRequestValidatorCacheSaverInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    protected $filesystem;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface
     */
    protected $yaml;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface $filesystem
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface $yaml
     * @param \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorToFilesystemAdapterInterface $filesystem,
        RestRequestValidatorToYamlAdapterInterface $yaml,
        RestRequestValidatorConfig $config
    ) {
        $this->filesystem = $filesystem;
        $this->yaml = $yaml;
        $this->config = $config;
    }

    /**
     * @deprecated Use {@link saveCacheForCodeBucket()} instead.
     *
     * @param array $validatorConfig
     * @param string $storeName
     *
     * @return void
     */
    public function save(array $validatorConfig, string $storeName): void
    {
        $this->filesystem->dumpFile(
            $this->getStoreCacheFilePath($storeName),
            $this->yaml->dump($validatorConfig)
        );
    }

    /**
     * @param array $validatorConfig
     * @param string $codeBucket
     *
     * @return void
     */
    public function saveCacheForCodeBucket(array $validatorConfig, string $codeBucket): void
    {
        $outdatedConfigFiles = glob($this->getCodeBucketCacheFilePath($codeBucket));

        if (!empty($outdatedConfigFiles)) {
            $this->filesystem->remove($outdatedConfigFiles);
        }

        $this->filesystem->dumpFile(
            $this->getCodeBucketCacheFilePath($codeBucket),
            $this->yaml->dump($validatorConfig)
        );
    }

    /**
     * @deprecated Use {@link getCodeBucketCacheFilePath()} instead.
     *
     * @param string $storeName
     *
     * @return string
     */
    protected function getStoreCacheFilePath(string $storeName): string
    {
        return sprintf($this->config->getCacheFilePathPattern(), $storeName);
    }

    /**
     * @param string $codeBucket
     *
     * @return string
     */
    protected function getCodeBucketCacheFilePath(string $codeBucket): string
    {
        return sprintf($this->config->getCodeBucketCacheFilePathPattern(), $codeBucket);
    }
}
