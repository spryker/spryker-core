<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage\Provider;

use Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidConfigurationException;
use Spryker\Service\FileSystem\Model\Exception\FileSystemStorageConfigNotFoundException;

class StorageConfigProvider implements StorageConfigProviderInterface
{

    /**
     * @var array
     */
    protected $configurationData;

    /**
     * @var array
     */
    protected $configCollection;

    /**
     * @param array $configurationData
     */
    public function __construct(array $configurationData)
    {
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
