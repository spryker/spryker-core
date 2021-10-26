<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Spryker\Service\Flysystem\Model\Provider\FilesystemProvider;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface;
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
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const SPRYKER_ADAPTER_CLASS = 'sprykerAdapterClass';

    /**
     * @return \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface
     */
    public function createFilesystemProvider(): FilesystemProviderInterface
    {
        return new FilesystemProvider(
            $this->getConfig(),
            $this->getFilesystemBuilderPluginCollection()
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\ReaderInterface
     */
    public function createReader()
    {
        return new Reader(
            $this->createFilesystemProvider(),
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\WriterInterface
     */
    public function createWriter()
    {
        return new Writer(
            $this->createFilesystemProvider(),
        );
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\StreamInterface
     */
    public function createStream()
    {
        return new Stream(
            $this->createFilesystemProvider(),
        );
    }

    /**
     * @return array<\Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface>
     */
    protected function getFilesystemBuilderPluginCollection()
    {
        return $this->getProvidedDependency(FlysystemDependencyProvider::PLUGIN_COLLECTION_FILESYSTEM_BUILDER);
    }
}
