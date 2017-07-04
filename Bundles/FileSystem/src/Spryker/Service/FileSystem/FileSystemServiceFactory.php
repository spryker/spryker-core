<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\FileSystem\Model\Adapter\FileSystemReader;
use Spryker\Service\FileSystem\Model\Adapter\FileSystemStream;
use Spryker\Service\FileSystem\Model\Adapter\FileSystemWriter;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemReaderInterface
     */
    public function createFileSystemReader()
    {
        return new FileSystemReader(
            $this->getFileSystemReaderPlugin()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemWriterInterface
     */
    public function createFileSystemWriter()
    {
        return new FileSystemWriter(
            $this->getFileSystemWriterPlugin()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemStreamInterface
     */
    public function createFileSystemStream()
    {
        return new FileSystemStream(
            $this->getFileSystemStreamPlugin()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemReaderPluginInterface
     */
    protected function getFileSystemReaderPlugin()
    {
        return $this->getProvidedDependency(FileSystemDependencyProvider::PLUGIN_READER);
    }

    /**
     * @return \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemWriterPluginInterface
     */
    protected function getFileSystemWriterPlugin()
    {
        return $this->getProvidedDependency(FileSystemDependencyProvider::PLUGIN_WRITER);
    }

    /**
     * @return \Spryker\Service\FileSystem\Dependency\Plugin\FileSystemStreamPluginInterface
     */
    protected function getFileSystemStreamPlugin()
    {
        return $this->getProvidedDependency(FileSystemDependencyProvider::PLUGIN_STREAM);
    }

}
