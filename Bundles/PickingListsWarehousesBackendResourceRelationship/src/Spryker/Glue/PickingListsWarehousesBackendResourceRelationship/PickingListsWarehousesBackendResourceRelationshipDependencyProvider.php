<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Facade\PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeBridge;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource\PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceBridge;

/**
 * @method \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\PickingListsWarehousesBackendResourceRelationshipConfig getConfig()
 */
class PickingListsWarehousesBackendResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PICKING_LIST = 'FACADE_PICKING_LIST';

    /**
     * @var string
     */
    public const RESOURCE_WAREHOUSES_BACKEND_API = 'RESOURCE_WAREHOUSES_BACKEND_API';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addPickingListFacade($container);
        $container = $this->addWarehousesBackendApiResource($container);

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
            return new PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeBridge(
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
    protected function addWarehousesBackendApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_WAREHOUSES_BACKEND_API, function (Container $container) {
            return new PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceBridge(
                $container->getLocator()->warehousesBackendApi()->resource(),
            );
        });

        return $container;
    }
}
