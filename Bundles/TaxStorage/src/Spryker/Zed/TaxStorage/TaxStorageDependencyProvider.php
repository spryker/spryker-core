<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxStorage\Dependency\Facade\TaxSetStorageToEventBehaviorFacadeBridge;

class TaxStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const OWN_CLIENT = 'OWN_CLIENT';

    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);

        /** REMOVE */
        $container[static::OWN_CLIENT] = function (Container $container) {
            return $container->getLocator()->taxStorage()->client();
        };
        /** REMOVE */

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new TaxSetStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }

}