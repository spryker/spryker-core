<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Spryker\Service\Flysystem\Exception\BuilderNotFoundException;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProvider;
use Spryker\Service\Flysystem\Model\Reader;
use Spryker\Service\Flysystem\Model\Stream;
use Spryker\Service\Flysystem\Model\Writer;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Flysystem\FlysystemConfig getConfig()
 */
class FlysystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface
     */
    public function createFilesystemProvider()
    {
        return new FilesystemProvider(
            $this->buildFilesystemCollection()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\ReaderInterface
     */
    public function createReader()
    {
        return new Reader(
            $this->createFilesystemProvider()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\WriterInterface
     */
    public function createWriter()
    {
        return new Writer(
            $this->createFilesystemProvider()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\StreamInterface
     */
    public function createStream()
    {
        return new Stream(
            $this->createFilesystemProvider()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer[]
     */
    protected function createConfigCollection()
    {
        $configCollection = [];
        foreach ($this->getConfig()->getFilesystemConfig() as $name => $configData) {
            $config = $this->createConfig($name, $configData);
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
    protected function createConfig($name, array $configData)
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
    protected function buildFilesystemCollection()
    {
        $configCollection = $this->createConfigCollection();

        $filesystemCollection = [];
        foreach ($configCollection as $name => $configTransfer) {
            $builder = $this->createFilesystemBuilder($configTransfer);
            $filesystemCollection[$name] = $builder->build();
        }

        return $filesystemCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @throws \Spryker\Service\Flysystem\Exception\BuilderNotFoundException
     *
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected function createFilesystemBuilder(FlysystemConfigTransfer $configTransfer)
    {
        $configTransfer->requireName();

        $builderClass = $configTransfer->getType();
        if (!$builderClass) {
            throw new BuilderNotFoundException(
                sprintf('FlysystemBuilder "%s" was not found', $configTransfer->getName())
            );
        }

        $builder = new $builderClass($configTransfer);

        return $builder;
    }

}
