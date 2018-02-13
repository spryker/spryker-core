<?php

namespace Spryker\Service\FileManager;

use Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemBridgeInterface;
use Spryker\Service\FileManager\Model\FileReader;
use Spryker\Service\FileManager\Model\FileReaderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method FileManagerServiceConfig getConfig()
 */
class FileManagerServiceFactory extends AbstractServiceFactory
{

    /**
     * @return FileManagerToFileSystemBridgeInterface
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileSystemService()
    {
        return $this->getProvidedDependency(FileManagerServiceDependencyProvider::FILE_SYSTEM_SERVICE);
    }

    /**
     * @return FileReaderInterface
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileReader()
    {
        return new FileReader($this->getFileSystemService(), $this->getConfig());
    }


}
