<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Generated\Shared\Transfer\FlysystemResourceTransfer;
use Generated\Shared\Transfer\FlysystemStorageConfigTransfer;
use Spryker\Service\Flysystem\Exception\FlysystemStorageBuilderNotFoundException;
use Spryker\Service\Flysystem\Model\Provider\FlysystemStorageProvider;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Flysystem\FlysystemConfig getConfig()
 */
class FlysystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceTransfer
     */
    public function createFlysystemResource(array $data)
    {
        return (new FlysystemResourceTransfer())
            ->fromArray($data, true);
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\Provider\FlysystemStorageProviderInterface
     */
    public function createStorageProvider()
    {
        return new FlysystemStorageProvider(
            $this->createFlysystemStorageCollection()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\FlysystemStorageConfigTransfer[]
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
     * @return \Generated\Shared\Transfer\FlysystemStorageConfigTransfer
     */
    protected function createStorageConfig($storageName, array $storageConfigData)
    {
        $type = $storageConfigData[FlysystemStorageConfigTransfer::TYPE];
        unset($storageConfigData[FlysystemStorageConfigTransfer::TYPE]);

        $configTransfer = new FlysystemStorageConfigTransfer();
        $configTransfer->setName($storageName);
        $configTransfer->setType($type);
        $configTransfer->setData($storageConfigData);

        return $configTransfer;
    }

    /**
     * @return \League\Flysystem\Filesystem[]
     */
    protected function createFlysystemStorageCollection()
    {
        $configCollection = $this->createStorageConfigCollection();

        $storageCollection = [];
        foreach ($configCollection as $storageName => $configStorageTransfer) {
            $builder = $this->createFlysystemBuilder($configStorageTransfer);
            $storageCollection[$storageName] = $builder->build();
        }

        return $storageCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemStorageConfigTransfer $storageConfigTransfer
     *
     * @throws \Spryker\Service\Flysystem\Exception\FlysystemStorageBuilderNotFoundException
     *
     * @return \Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface
     */
    protected function createFlysystemBuilder(FlysystemStorageConfigTransfer $storageConfigTransfer)
    {
        $storageConfigTransfer->requireName();

        $builderClass = $storageConfigTransfer->getType();
        if (!$builderClass) {
            throw new FlysystemStorageBuilderNotFoundException(
                sprintf('FlysystemStorageBuilder "%s" was not found', $storageConfigTransfer->getName())
            );
        }

        //TODO remove magic
        $builder = new $builderClass($storageConfigTransfer);

        return $builder;
    }

}
