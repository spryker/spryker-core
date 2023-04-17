<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource\PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceBridge;

class PickingListsSalesOrdersBackendResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_SALES_ORDERS_BACKEND_API = 'RESOURCE_SALES_ORDERS_BACKEND_API';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addSalesOrdersBackendApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addSalesOrdersBackendApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_SALES_ORDERS_BACKEND_API, function (Container $container) {
            return new PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceBridge(
                $container->getLocator()->salesOrdersBackendApi()->resource(),
            );
        });

        return $container;
    }
}
