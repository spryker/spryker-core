<?php

namespace Spryker\Zed\FileManagerStorage;

use Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToLocaleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileManagerStorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const FACADE_LOCALE= 'FACADE_LOCALE';


    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            $eventBehaviourFacade = $container->getLocator()->eventBehavior()->facade();
            return new FileManagerStorageToEventBehaviorFacadeBridge($eventBehaviourFacade);
        };

        $container[static::FACADE_LOCALE] = function (Container $container) {
            $localeFacade = $container->getLocator()->locale()->facade();
            return new FileManagerStorageToLocaleFacadeBridge($localeFacade);
        };

        return $container;
    }

}