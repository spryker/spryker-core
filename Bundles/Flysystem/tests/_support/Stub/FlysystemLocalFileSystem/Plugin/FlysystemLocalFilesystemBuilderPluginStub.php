<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Flysystem\Stub\FlysystemLocalFileSystem\Plugin;

use Flysystem\Stub\FlysystemLocalFileSystem\Adapter\LocalAdapterBuilderStub;
use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

class FlysystemLocalFilesystemBuilderPluginStub extends AbstractPlugin implements FlysystemFilesystemBuilderPluginInterface
{

    /**
     * @param string $type
     *
     * @return bool
     */
    public function acceptType($type)
    {
        return $type === get_class($this);
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \League\Flysystem\PluginInterface[] $flysystemPluginCollection
     *
     * @return \League\Flysystem\Filesystem
     */
    public function build(FlysystemConfigTransfer $configTransfer, array $flysystemPluginCollection = [])
    {
        $adapterConfig = $this->buildAdapterConfig($configTransfer);
        $this->assertAdapterConfig($adapterConfig);

        $filesystem = $this->buildFilesystem($configTransfer, $adapterConfig);
        $filesystem = $this->provideFlysystemPlugins($filesystem, $flysystemPluginCollection);

        return $filesystem;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     *
     * @return \Generated\Shared\Transfer\FlysystemConfigLocalTransfer
     */
    protected function buildAdapterConfig(FlysystemConfigTransfer $configTransfer)
    {
        $adapterConfigTransfer = new FlysystemConfigLocalTransfer();
        $adapterConfigTransfer->fromArray($configTransfer->getAdapterConfig(), true);

        return $adapterConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigLocalTransfer $adapterConfigTransfer
     *
     * @return void
     */
    protected function assertAdapterConfig(FlysystemConfigLocalTransfer $adapterConfigTransfer)
    {
        $adapterConfigTransfer->requirePath();
        $adapterConfigTransfer->requireRoot();
    }

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     * @param \Generated\Shared\Transfer\FlysystemConfigLocalTransfer $adapterConfigTransfer
     *
     * @return \League\Flysystem\Filesystem
     */
    protected function buildFilesystem(FlysystemConfigTransfer $configTransfer, FlysystemConfigLocalTransfer $adapterConfigTransfer)
    {
        $config = $configTransfer->getFlysystemConfig() ?: [];
        $adapterBuilder = new LocalAdapterBuilderStub($configTransfer, $adapterConfigTransfer);
        $adapter = $adapterBuilder->build();

        return new Filesystem($adapter, $config);
    }

    /**
     * @param \League\Flysystem\Filesystem $filesystem
     * @param \League\Flysystem\PluginInterface[] $filesystemPluginCollection
     *
     * @return \League\Flysystem\Filesystem
     */
    protected function provideFlysystemPlugins(Filesystem $filesystem, array $filesystemPluginCollection)
    {
        foreach ($filesystemPluginCollection as $plugin) {
            $filesystem->addPlugin($plugin);
        }

        return $filesystem;
    }

}
