<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidConfigurationException;
use Spryker\Service\FileSystem\Model\Exception\FileSystemStorageConfigNotFoundException;

class StorageConfig
{

    const NAME = 'name';
    const ROOT = 'root';
    const TYPE = 'type';

    /**
     * @var string
     */
    protected $root;

    /**
     * @var array
     */
    protected $configurationData;

    /**
     * @var array
     */
    protected $configCollection;

    /**
     * @param string $root
     * @param array $configurationData
     */
    public function __construct($root, array $configurationData)
    {
        $this->root = $root;
        $this->configurationData = $configurationData;
    }

    /**
     * @return array
     */
    public function getStorageDefinitionCollection()
    {
        if ($this->configCollection === null) {
            $this->configCollection = [];
            foreach ($this->configurationData as $storageName => $storageConfig) {
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

        $config[static::NAME] = $storageName;
        $config[static::ROOT] = $this->root;

        return $config;
    }

    /**
     * @param string $storageName
     *
     * @return string
     */
    public function getBuilderTypFromConfig($storageName)
    {
        return $this->getStorageConfigByName($storageName)[static::TYPE];
    }

    /**
     * @param string $storageName
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidConfigurationException
     *
     * @return void
     */
    protected function validateConfigType($storageName, array $config)
    {
        if (!array_key_exists(static::TYPE, $config)) {
            throw new FileSystemInvalidConfigurationException(sprintf(
                'Missing configuration "%s" property in FileSystemStorage for "%s"',
                static::TYPE,
                $storageName
            ));
        }
    }

    /**
     * @param string $storageName
     *
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemStorageConfigNotFoundException
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
