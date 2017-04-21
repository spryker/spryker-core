<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Generated\Shared\Transfer\FileSystemResourceTransfer;
use Generated\Shared\Transfer\FileSystemStorageConfigTransfer;
use Spryker\Service\FileSystem\Exception\FileSystemStorageBuilderNotFoundException;
use Spryker\Service\FileSystem\Model\Provider\FileSystemStorageProvider;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceTransfer
     */
    public function createFileSystemResource(array $data)
    {
        return (new FileSystemResourceTransfer())
            ->fromArray($data, true);
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Provider\FileSystemStorageProviderInterface
     */
    public function createStorageProvider()
    {
        return new FileSystemStorageProvider(
            $this->createFileSystemStorageCollection()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigTransfer[]
     */
    protected function createStorageConfigCollection()
    {
        $storageCollection = [];
        foreach ($this->getConfig()->getStorageConfig() as $storageName => $storageConfigData) {
            $storageConfig = $this->createStorageConfig($storageName, $storageConfigData);
            $storageCollection[$storageName] = $storageConfig;
        }

        return $storageCollection;
    }

    /**
     * @param string $storageName
     * @param array $storageConfigData
     *
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigTransfer
     */
    protected function createStorageConfig($storageName, array $storageConfigData)
    {
        $type = $storageConfigData[FileSystemStorageConfigTransfer::TYPE];
        unset($storageConfigData[FileSystemStorageConfigTransfer::TYPE]);

        $configTransfer = new FileSystemStorageConfigTransfer();
        $configTransfer->setName($storageName);
        $configTransfer->setType($type);
        $configTransfer->setData($storageConfigData);

        return $configTransfer;
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface[]
     */
    protected function createFileSystemStorageCollection()
    {
        $configCollection = $this->createStorageConfigCollection();

        $storageCollection = [];
        foreach ($configCollection as $storageName => $configStorageTransfer) {
            $builder = $this->createFileSystemBuilder($configStorageTransfer);
            $storageCollection[$storageName] = $builder->build();
        }

        return $storageCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigTransfer $storageConfigTransfer
     *
     * @throws \Spryker\Service\FileSystem\Exception\FileSystemStorageBuilderNotFoundException
     *
     * @return \Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface
     */
    protected function createFileSystemBuilder(FileSystemStorageConfigTransfer $storageConfigTransfer)
    {
        $storageConfigTransfer->requireName();

        $builderClass = $storageConfigTransfer->getType();
        if (!$builderClass) {
            throw new FileSystemStorageBuilderNotFoundException(
                sprintf('FileSystemStorageBuilder "%s" was not found', $storageConfigTransfer->getName())
            );
        }

        $builder = new $builderClass($storageConfigTransfer);

        return $builder;
    }

}
