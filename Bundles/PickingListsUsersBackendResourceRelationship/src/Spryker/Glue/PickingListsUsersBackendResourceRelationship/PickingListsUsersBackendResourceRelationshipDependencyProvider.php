<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Facade\PickingListsUsersBackendResourceRelationshipToPickingListFacadeBridge;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource\PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceBridge;

/**
 * @method \Spryker\Glue\PickingListsUsersBackendResourceRelationship\PickingListsUsersBackendResourceRelationshipConfig getConfig()
 */
class PickingListsUsersBackendResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
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
            return new PickingListsUsersBackendResourceRelationshipToPickingListFacadeBridge(
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
            return new PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceBridge(
                $container->getLocator()->usersBackendApi()->resource(),
            );
        });

        return $container;
    }
}
