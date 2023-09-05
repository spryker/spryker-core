<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceBridge;

/**
 * @method \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\ShipmentTypesServicePointsResourceRelationshipConfig getConfig()
 */
class ShipmentTypesServicePointsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_SERVICE_POINTS_REST_API = 'RESOURCE_SERVICE_POINTS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addServicePointsRestApiResource($container);

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
            return new ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceBridge(
                $container->getLocator()->servicePointsRestApi()->resource(),
            );
        });

        return $container;
    }
}
