<?php

namespace Spryker\Zed\FileManager\Business;

use Spryker\Service\FileManager\FileManagerService;
use Spryker\Zed\FileManager\Business\Model\FileFinder;
use Spryker\Zed\FileManager\Business\Model\FileRemover;
use Spryker\Zed\FileManager\Business\Model\FileRollback;
use Spryker\Zed\FileManager\Business\Model\FileSaver;
use Spryker\Zed\FileManager\Business\Model\FileVersion;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * Class FileManagerBusinessFactory
 *
 * @method FileManagerQueryContainer getQueryContainer()
 */
class FileManagerBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return FileSaver
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileSaver()
    {
        return new FileSaver(
            $this->getQueryContainer(),
            $this->createFileVersion(),
            $this->createFileFinder(),
            $this->getFileManagerService()
        );
    }

    /**
     * @return FileRollback
     */
    public function createFileRollback()
    {
        return new FileRollback(
            $this->createFileFinder(),
            $this->createFileVersion()
        );
    }

    /**
     * @return FileRemover
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileRemover()
    {
        return new FileRemover(
            $this->createFileFinder(),
            $this->getFileManagerService()
        );
    }

    /**
     * @return FileVersion
     */
    public function createFileVersion()
    {
        return new FileVersion($this->createFileFinder());
    }

    /**
     * @return FileFinder
     */
    public function createFileFinder()
    {
        return new FileFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return FileManagerService
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileManagerService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::SERVICE_FILE_MANAGER);
    }

}
