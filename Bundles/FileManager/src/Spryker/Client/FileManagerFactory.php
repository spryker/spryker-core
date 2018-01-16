<?php


namespace Spryker\Client\FileManager;


use Spryker\Client\FileManager\Model\Adapter\AdapterInterface;
use Spryker\Client\FileManager\Model\Proxy\FileManagerProxy;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\FileManager\FileManagerConfig getConfig()
 */
class FileManagerFactory extends AbstractFactory
{

    /**
     * @return FileManagerProxy
     */
    public function createFileManagerProxy()
    {
        return new FileManagerProxy($this->getFileManagerAdapter());
    }

    /**
     * @return AdapterInterface
     */
    public function getFileManagerAdapter()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::FILE_MANAGER_ADAPTER);
    }

}