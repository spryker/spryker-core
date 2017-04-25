<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business;

use Spryker\Zed\FileSystem\Business\Model\Adapter\Flysystem\FileSystemReader;
use Spryker\Zed\FileSystem\Business\Model\Adapter\Flysystem\FileSystemStream;
use Spryker\Zed\FileSystem\Business\Model\Adapter\Flysystem\FileSystemWriter;
use Spryker\Zed\FileSystem\Business\Model\FileSystemAdapter;
use Spryker\Zed\FileSystem\FileSystemDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 */
class FileSystemBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\FileSystem\Business\Model\FileSystemAdapterInterface
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
     * @return \Spryker\Zed\FileSystem\Business\Model\FileSystemReaderInterface
     */
    protected function createFlysystemAdapterReader()
    {
        return new FileSystemReader(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Zed\FileSystem\Business\Model\FileSystemWriterInterface
     */
    protected function createFlysystemAdapterWriter()
    {
        return new FileSystemWriter(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Zed\FileSystem\Business\Model\FileSystemStreamInterface
     */
    protected function createFlysystemAdapterStream()
    {
        return new FileSystemStream(
            $this->getFlysystemService()
        );
    }

    /**
     * @return \Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemInterface
     */
    protected function getFlysystemService()
    {
        return $this->getProvidedDependency(FileSystemDependencyProvider::SERVICE_FLYSYSTEM);
    }

}
