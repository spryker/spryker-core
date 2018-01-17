<?php

namespace Spryker\Service\FileManager;


use Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface;
use Spryker\Service\FileManager\Model\Adapter\FileManager;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * Class FileManagerServiceFactory
 *
 * @method FileManagerDependencyProvider getDependencyProvider()
 */
class FileManagerServiceFactory extends AbstractServiceFactory
{

    /**
     * @return FileManager
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileManagerAdapter()
    {
        return new FileManager($this->getFileManagerPlugin());
    }

    /**
     * @return FileManagerPluginInterface
     *
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getFileManagerPlugin()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::FILE_MANAGER_PLUGIN);
    }

}