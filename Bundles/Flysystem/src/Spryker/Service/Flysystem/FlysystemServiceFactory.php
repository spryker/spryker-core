<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Generated\Shared\Transfer\FlysystemResourceTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\Flysystem\Exception\FlysystemStorageBuilderNotFoundException;
use Spryker\Service\Flysystem\Model\Provider\FlysystemProvider;
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
     * @return \Spryker\Service\Flysystem\Model\Provider\FlysystemProviderInterface
     */
    public function createFilesystemProvider()
    {
        return new FlysystemProvider(
            $this->createFilesystemCollection()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer[]
     */
    protected function createFilesystemConfigCollection()
    {
        $configCollection = [];
        foreach ($this->getConfig()->getFilesystemConfig() as $name => $configData) {
            $config = $this->createFilesystemConfig($name, $configData);
            $configCollection[$name] = $config;
        }

        return $configCollection;
    }

    /**
     * @param string $name
     * @param array $configData
     *
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected function createFilesystemConfig($name, array $configData)
    {
        $type = $configData[FlysystemConfigTransfer::TYPE];
        unset($configData[FlysystemConfigTransfer::TYPE]);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName($name);
        $configTransfer->setType($type);
        $configTransfer->setData($configData);

        return $configTransfer;
    }

    /**
     * @return \League\Flysystem\Filesystem[]
     */
    protected function createFilesystemCollection()
    {
        $configCollection = $this->createFilesystemConfigCollection();

        $filesystemCollection = [];
        foreach ($configCollection as $name => $configTransfer) {
            $builder = $this->createFlysystemBuilder($configTransfer);
            $filesystemCollection[$name] = $builder->build();
        }

        return $filesystemCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @throws \Spryker\Service\Flysystem\Exception\FlysystemStorageBuilderNotFoundException
     *
     * @return \Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface
     */
    protected function createFlysystemBuilder(FlysystemConfigTransfer $configTransfer)
    {
        $configTransfer->requireName();

        $builderClass = $configTransfer->getType();
        if (!$builderClass) {
            throw new FlysystemStorageBuilderNotFoundException(
                sprintf('FlysystemStorageBuilder "%s" was not found', $configTransfer->getName())
            );
        }

        //TODO remove magic
        $builder = new $builderClass($configTransfer);

        return $builder;
    }

}
