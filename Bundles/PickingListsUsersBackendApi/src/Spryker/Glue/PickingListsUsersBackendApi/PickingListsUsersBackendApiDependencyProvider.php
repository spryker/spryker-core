<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsUsersBackendApi\Dependency\Facade\PickingListsUsersBackendApiToPickingListFacadeBridge;
use Spryker\Glue\PickingListsUsersBackendApi\Dependency\Resource\PickingListsUsersBackendApiToUsersBackendApiResourceBridge;

/**
 * @method \Spryker\Glue\PickingListsUsersBackendApi\PickingListsUsersBackendApiConfig getConfig()
 */
class PickingListsUsersBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PICKING_LIST = 'FACADE_PICKING_LIST';

    /**
     * @var string
     */
    public const RESOURCE_USERS_BACKEND_API = 'RESOURCE_USERS_BACKEND_API';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addPickingListFacade($container);
        $container = $this->addUsersBackendApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addPickingListFacade(Container $container): Container
    {
        $container->set(static::FACADE_PICKING_LIST, function (Container $container) {
            return new PickingListsUsersBackendApiToPickingListFacadeBridge(
                $container->getLocator()->pickingList()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUsersBackendApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_USERS_BACKEND_API, function (Container $container) {
            return new PickingListsUsersBackendApiToUsersBackendApiResourceBridge(
                $container->getLocator()->usersBackendApi()->resource(),
            );
        });

        return $container;
    }
}
