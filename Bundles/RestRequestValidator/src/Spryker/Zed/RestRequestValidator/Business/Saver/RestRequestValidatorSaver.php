<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Saver;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig;

class RestRequestValidatorSaver implements RestRequestValidatorSaverInterface
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function store(array $validatorConfig, StoreTransfer $storeTransfer): void
    {
        $this->filesystem->remove($this->getOutdatedConfig($storeTransfer));

        $this->filesystem->dumpFile(
            $this->getStoreCacheFilePath($storeTransfer),
            $this->yaml->dump($validatorConfig)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function getStoreCacheFilePath(StoreTransfer $storeTransfer): string
    {
        return sprintf($this->config->getCacheFilePathPattern(), $storeTransfer->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function getOutdatedConfig(StoreTransfer $storeTransfer): array
    {
        return glob(sprintf($this->config->getCacheFilePathPattern(), $storeTransfer->getName()));
    }
}
