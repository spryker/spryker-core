<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business;

use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\FileManager\Business\Model\FileContent;
use Spryker\Zed\FileManager\Business\Model\FileFinder;
use Spryker\Zed\FileManager\Business\Model\FileRemover;
use Spryker\Zed\FileManager\Business\Model\FileRollback;
use Spryker\Zed\FileManager\Business\Model\FileSaver;
use Spryker\Zed\FileManager\Business\Model\FileVersion;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 */
class FileManagerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileSaverInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileSaver()
    {
        return new FileSaver(
            $this->getQueryContainer(),
            $this->createFileVersion(),
            $this->createFileFinder(),
            $this->createFileContent(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileRollbackInterface
     */
    public function createFileRollback()
    {
        return new FileRollback(
            $this->createFileFinder(),
            $this->createFileVersion()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileRemoverInterface
     */
    public function createFileRemover()
    {
        return new FileRemover(
            $this->createFileFinder(),
            $this->getFileManagerService()
        );
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    public function createFileVersion()
    {
        return new FileVersion($this->createFileFinder());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    public function createFileFinder()
    {
        return new FileFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return FileContent
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileContent()
    {
        return new FileContent(
            $this->getFileSystemService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileManagerService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::SERVICE_FILE_MANAGER);
    }

    /**
     * @return FileSystemServiceInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileSystemService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::SERVICE_FILE_SYSTEM);
    }
}
