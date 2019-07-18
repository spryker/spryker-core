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
     * @param array $validatorConfig
     * @param string $storeName
     *
     * @return void
     */
    public function save(array $validatorConfig, string $storeName): void
    {
        $outdatedConfigFiles = $this->getOutdatedConfig($storeName);
        if (!empty($outdatedConfigFiles)) {
            $this->filesystem->remove($outdatedConfigFiles);
        }

        $this->filesystem->dumpFile(
            $this->getStoreCacheFilePath($storeName),
            $this->yaml->dump($validatorConfig)
        );
    }

    /**
     * @param string $storeName
     *
     * @return string
     */
    protected function getStoreCacheFilePath(string $storeName): string
    {
        return sprintf($this->config->getCacheFilePathPattern(), $storeName);
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    protected function getOutdatedConfig(string $storeName): array
    {
        return glob(sprintf($this->config->getCacheFilePathPattern(), $storeName));
    }
}
