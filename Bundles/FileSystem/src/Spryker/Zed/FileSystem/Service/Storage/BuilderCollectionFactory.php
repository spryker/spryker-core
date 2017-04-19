<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Storage;

use Spryker\Zed\FileSystem\FileSystemConfig;
use Spryker\Zed\FileSystem\Service\Exception\FileSystemStorageBuilderNotFoundException;

class BuilderCollectionFactory implements BuilderCollectionFactoryInterface
{

    /**
     * @var \Spryker\Zed\FileSystem\FileSystemConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\FileSystem\FileSystemConfig $config
     */
    public function __construct(FileSystemConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\BuilderInterface[]
     */
    public function createCollection()
    {
        $storageCollection = [];
        foreach ($this->config->getStorageDefinitionCollection() as $storageName => $storageConfig) {
            $builder = $this->createBuilder($storageName, $storageConfig);
            $storageCollection[$storageName] = $builder->build();
        }

        return $storageCollection;
    }

    /**
     * @param string $storageName
     * @param array $storageConfig
     *
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemStorageBuilderNotFoundException
     *
     * @return \Spryker\Zed\FileSystem\Service\Storage\BuilderInterface
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
            'Spryker\Zed\FileSystem\Service\Storage\Builder\%sBuilder',
            $this->config->getBuilderTypFromConfig($name)
        );
    }

}
