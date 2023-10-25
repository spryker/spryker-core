<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientBridge;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientBridge;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientBridge;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Resource\ShipmentTypeServicePointsRestApiToServicePointsRestApiResourceBridge;

/**
 * @method \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig getConfig()
 */
class ShipmentTypeServicePointsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_SERVICE_POINTS_REST_API = 'RESOURCE_SERVICE_POINTS_REST_API';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_SHIPMENT_TYPE_STORAGE = 'CLIENT_SHIPMENT_TYPE_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_STORAGE = 'CLIENT_SERVICE_POINT_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addServicePointsRestApiResource($container);
        $container = $this->addStoreClient($container);
        $container = $this->addShipmentTypeStorageClient($container);
        $container = $this->addServicePointStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addServicePointsRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_SERVICE_POINTS_REST_API, function (Container $container) {
            return new ShipmentTypeServicePointsRestApiToServicePointsRestApiResourceBridge(
                $container->getLocator()->servicePointsRestApi()->resource(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ShipmentTypeServicePointsRestApiToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addShipmentTypeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT_TYPE_STORAGE, function (Container $container) {
            return new ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientBridge(
                $container->getLocator()->shipmentTypeStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addServicePointStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_STORAGE, function (Container $container) {
            return new ShipmentTypeServicePointsRestApiToServicePointStorageClientBridge(
                $container->getLocator()->servicePointStorage()->client(),
            );
        });

        return $container;
    }
}
