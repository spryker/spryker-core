<?php

namespace Spryker\Zed\FileManagerGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileManagerGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_FILE_MANAGER = 'FACADE_FILE_MANAGER';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const QUERY_CONTAINER_FILE_MANAGER = 'QUERY_CONTAINER_FILE_MANAGER';

    /**
     * @param Container $container
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_FILE_MANAGER] = function(Container $container) {
            return $container->getLocator()->fileManager()->facade();
        };

        $container[static::QUERY_CONTAINER_FILE_MANAGER] = function(Container $container) {
            return $container->getLocator()->fileManager()->queryContainer();
        };

        $container[static::FACADE_LOCALE] = function(Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return parent::provideCommunicationLayerDependencies($container);
    }

}