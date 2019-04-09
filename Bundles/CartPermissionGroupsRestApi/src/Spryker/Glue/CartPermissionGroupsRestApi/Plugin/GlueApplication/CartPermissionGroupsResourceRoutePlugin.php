<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer;
use Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class CartPermissionGroupsResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritdoc}
     *  - Configures available actions for `cart-permission-groups` resource.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        return $resourceRouteCollection->addGet('get', true);
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
        return CartPermissionGroupsRestApiConfig::RESOURCE_CART_PERMISSION_GROUPS;
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
        return CartPermissionGroupsRestApiConfig::CONTROLLER_CART_PERMISSION_GROUPS;
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
        return RestCartPermissionGroupsAttributesTransfer::class;
    }
}
