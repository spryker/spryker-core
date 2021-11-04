<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Provider;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\FilesystemOperator;
use Spryker\Service\Flysystem\Exception\BuilderNotFoundException;
use Spryker\Service\Flysystem\Exception\FilesystemNotFoundException;
use Spryker\Service\Flysystem\FlysystemConfig;

class FilesystemProvider implements FilesystemProviderInterface
{
    /**
     * @var string
     */
    protected const SPRYKER_ADAPTER_CLASS = 'sprykerAdapterClass';

    /**
     * @var array<\League\Flysystem\FilesystemOperator>
     */
    protected $filesystemCollection;

    /**
     * @var \Spryker\Service\Flysystem\FlysystemConfig
     */
    protected $config;

    /**
     * @var array<\Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface>
     */
    protected $filesystemBuilderPluginCollection;

    /**
     * @param \Spryker\Service\Flysystem\FlysystemConfig $config
     * @param array<\Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface> $filesystemBuilderPluginCollection
     */
    public function __construct(
        FlysystemConfig $config,
        array $filesystemBuilderPluginCollection
    ) {
        $this->config = $config;
        $this->filesystemBuilderPluginCollection = $filesystemBuilderPluginCollection;

        $this->buildFilesystemCollection();
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\Flysystem\Exception\FilesystemNotFoundException
     *
     * @return \League\Flysystem\FilesystemOperator
     */
    public function getFilesystemByName($name): FilesystemOperator
    {
        if (!array_key_exists($name, $this->filesystemCollection)) {
            throw new FilesystemNotFoundException(
                sprintf('Flysystem "%s" was not found', $name),
            );
        }

        return $this->filesystemCollection[$name];
    }

    /**
     * @return array<\League\Flysystem\FilesystemOperator>
     */
    public function getFilesystemCollection(): array
    {
        return $this->filesystemCollection;
    }

    /**
     * @return void
     */
    protected function buildFilesystemCollection(): void
    {
        foreach ($this->createConfigCollection() as $name => $configTransfer) {
            $this->filesystemCollection[$name] = $this->buildFilesystemByType($configTransfer);
        }
    }

    /**
     * @return array<\Generated\Shared\Transfer\FlysystemConfigTransfer>
     */
    protected function createConfigCollection(): array
    {
        $flysystemConfigTransferCollection = [];
        foreach ($this->config->getFilesystemConfig() as $name => $configData) {
            $flysystemConfigTransfer = $this->createFlysystemConfigTransfer($name, $configData);
            $this->assertFlysystemConfigTransfer($flysystemConfigTransfer);
            $flysystemConfigTransferCollection[$name] = $flysystemConfigTransfer;
        }

        return $flysystemConfigTransferCollection;
    }

    /**
     * @param string $name
     * @param array<string, mixed> $configData
     *
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected function createFlysystemConfigTransfer(string $name, array $configData): FlysystemConfigTransfer
    {
        $type = $configData[static::SPRYKER_ADAPTER_CLASS];
        unset($configData[static::SPRYKER_ADAPTER_CLASS]);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName($name);
        $configTransfer->setType($type);
        $configTransfer->setAdapterConfig($configData);

        $configTransfer->setFlysystemConfig(
            $this->config->getFlysystemConfig(),
        );

        return $configTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $flysystemConfigTransfer
     *
     * @return void
     */
    protected function assertFlysystemConfigTransfer(FlysystemConfigTransfer $flysystemConfigTransfer): void
    {
        $flysystemConfigTransfer->requireName();
        $flysystemConfigTransfer->requireType();
        $flysystemConfigTransfer->requireAdapterConfig();
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @throws \Spryker\Service\Flysystem\Exception\BuilderNotFoundException
     *
     * @return \League\Flysystem\FilesystemOperator
     */
    protected function buildFilesystemByType(FlysystemConfigTransfer $configTransfer): FilesystemOperator
    {
        foreach ($this->filesystemBuilderPluginCollection as $filesystemBuilderPlugin) {
            if ($filesystemBuilderPlugin->acceptType($configTransfer->getTypeOrFail())) {
                return $filesystemBuilderPlugin->build($configTransfer);
            }
        }

        throw new BuilderNotFoundException(sprintf(
            'FlysystemFileSystemBuilderPlugin "%s" was not found',
            $configTransfer->getNameOrFail(),
        ));
    }
}
