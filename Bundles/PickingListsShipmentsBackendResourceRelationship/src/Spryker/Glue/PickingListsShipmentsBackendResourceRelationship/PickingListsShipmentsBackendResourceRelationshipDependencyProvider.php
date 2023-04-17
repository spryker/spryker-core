<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource\PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceBridge;

/**
 * @method \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\PickingListsShipmentsBackendResourceRelationshipConfig getConfig()
 */
class PickingListsShipmentsBackendResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_SHIPMENTS_BACKEND_API = 'RESOURCE_SHIPMENTS_BACKEND_API';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addShipmentsBackendApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addShipmentsBackendApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_SHIPMENTS_BACKEND_API, function (Container $container) {
            return new PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceBridge(
                $container->getLocator()->shipmentsBackendApi()->resource(),
            );
        });

        return $container;
    }
}
