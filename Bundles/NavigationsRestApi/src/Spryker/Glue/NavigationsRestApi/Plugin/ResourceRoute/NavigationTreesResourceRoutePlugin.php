<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Plugin\ResourceRoute;

use Generated\Shared\Transfer\RestNavigationTreeAttributesTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig;

class NavigationTreesResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        return $resourceRouteCollection
            ->addGet(NavigationsRestApiConfig::ACTION_NAVIGATION_TREES_GET, false);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return NavigationsRestApiConfig::RESOURCE_NAVIGATION_TREES;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return NavigationsRestApiConfig::CONTROLLER_NAVIGATION_TREES;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestNavigationTreeAttributesTransfer::class;
    }
}
