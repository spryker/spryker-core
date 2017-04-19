<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage;

use Spryker\Service\FileSystem\Model\Exception\FileSystemStorageBuilderNotFoundException;
use Spryker\Service\FileSystem\Model\Storage\Provider\StorageConfigProviderInterface;

class StorageBuilderProvider implements StorageBuilderProviderInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Model\Storage\Provider\StorageConfigProviderInterface
     */
    protected $configProvider;

    /**
     * @param \Spryker\Service\FileSystem\Model\Storage\Provider\StorageConfigProviderInterface $configProvider
     */
    public function __construct(StorageConfigProviderInterface $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\BuilderInterface[]
     */
    public function createCollection()
    {
        $storageCollection = [];
        foreach ($this->configProvider->getStorageDefinitionCollection() as $storageName => $storageConfig) {
            $builder = $this->createBuilder($storageName, $storageConfig);
            $storageCollection[$storageName] = $builder->build();
        }

        return $storageCollection;
    }

    /**
     * @param string $storageName
     * @param array $storageConfig
     *
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemStorageBuilderNotFoundException
     *
     * @return \Spryker\Service\FileSystem\Model\Storage\BuilderInterface
     */
    protected function createBuilder($storageName, array $storageConfig)
    {
        $builderClass = $this->resolveBuilderClassName($storageName);

        if (!$builderClass) {
            throw new FileSystemStorageBuilderNotFoundException(
                sprintf('FileSystemStorageBuilder "%s" was not found', $storageName)
            );
        }

        $builder = new $builderClass($storageConfig);

        return $builder;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function resolveBuilderClassName($name)
    {
        return sprintf(
            'Spryker\Service\FileSystem\Model\Storage\Builder\%sBuilder',
            $this->configProvider->getBuilderTypFromConfig($name)
        );
    }

}
