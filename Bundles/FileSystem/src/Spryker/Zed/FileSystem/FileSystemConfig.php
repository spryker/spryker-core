<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem;

use Spryker\Shared\FileSystem\FileSystemConfig as SharedFileSystemConfig;
use Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidConfigurationException;
use Spryker\Zed\FileSystem\Service\Exception\FileSystemStorageConfigNotFoundException;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FileSystemConfig extends AbstractBundleConfig
{

    const NAME = 'name';
    const ROOT = 'root';
    const TYPE = 'type';

    /**
     * @var array
     */
    protected $configCollection;

    /**
     * @return array
     */
    public function getStorageDefinitionCollection()
    {
        if ($this->configCollection === null) {
            $this->configCollection = [];
            $data = $this->get(SharedFileSystemConfig::FILESYSTEM_STORAGE);
            foreach ($data as $storageName => $storageConfig) {
                $this->configCollection[$storageName] = $this->setupStorageConfig($storageName, $storageConfig);
            }
        }

        return $this->configCollection;
    }

    /**
     * @param string $storageName
     *
     * @return array
     */
    public function getStorageConfigByName($storageName)
    {
        $this->validateConfig($storageName);

        return $this->configCollection[$storageName];
    }

    /**
     * @param string $storageName
     * @param array $config
     *
     * @return array
     */
    protected function setupStorageConfig($storageName, array $config)
    {
        $this->validateConfigType($storageName, $config);

        $config[self::NAME] = $storageName;
        $config[self::ROOT] = $this->get(SharedFileSystemConfig::FILESYSTEM_STORAGE_ROOT);

        return $config;
    }

    /**
     * @param string $storageName
     *
     * @return string
     */
    public function getBuilderTypFromConfig($storageName)
    {
        return $this->getStorageConfigByName($storageName)[self::TYPE];
    }

    /**
     * @param string $storageName
     * @param array $config
     *
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidConfigurationException
     *
     * @return void
     */
    protected function validateConfigType($storageName, array $config)
    {
        if (!array_key_exists(self::TYPE, $config)) {
            throw new FileSystemInvalidConfigurationException(sprintf(
                'Missing configuration "%s" property in FileSystemStorage for "%s"',
                self::TYPE,
                $storageName
            ));
        }
    }

    /**
     * @param string $storageName
     *
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemStorageConfigNotFoundException
     *
     * @return void
     */
    protected function validateConfig($storageName)
    {
        if (!array_key_exists($storageName, $this->getStorageDefinitionCollection())) {
            throw new FileSystemStorageConfigNotFoundException(sprintf(
                'FileSystemStorageConfig "%s" was not found. Define it in the configuration file',
                $storageName
            ));
        }
    }

}
