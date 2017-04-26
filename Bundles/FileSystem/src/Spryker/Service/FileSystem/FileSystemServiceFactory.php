<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\FileSystem\Model\Adapter\Flysystem\FileSystemReader;
use Spryker\Service\FileSystem\Model\Adapter\Flysystem\FileSystemStream;
use Spryker\Service\FileSystem\Model\Adapter\Flysystem\FileSystemWriter;
use Spryker\Service\FileSystem\Model\FileSystemAdapter;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemAdapterInterface
     */
    public function createFileSystemAdapter()
    {
        return new FileSystemAdapter(
            $this->createFlysystemAdapterReader(),
            $this->createFlysystemAdapterWriter(),
            $this->createFlysystemAdapterStream()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemReaderInterface
     */
    protected function createFlysystemAdapterReader()
    {
        return new FileSystemReader(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemWriterInterface
     */
    protected function createFlysystemAdapterWriter()
    {
        return new FileSystemWriter(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemStreamInterface
     */
    protected function createFlysystemAdapterStream()
    {
        return new FileSystemStream(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Service\FileSystem\Dependency\Service\FileSystemToFlysystemInterface
     */
    protected function getFlysystemService()
    {
        return $this->getProvidedDependency(FileSystemDependencyProvider::SERVICE_FLYSYSTEM);
    }

}
