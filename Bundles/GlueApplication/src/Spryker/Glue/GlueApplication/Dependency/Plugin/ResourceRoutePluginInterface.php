<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Glue\GlueApplication\Dependency\Plugin;

interface ResourceRoutePluginInterface
{
    /**
     * @api
     *
     * Configuration for resource routing, how http methods map to controller actions, is action is protected, also possible
     * to add additional contextual data for action for later access when processing controller action.
     *
     * @param \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface;

    /**
     * @api
     *
     * Resource name this plugins handles, must be plural string. This name also is matched with request path where resource
     * is provided.
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * @api
     *
     * Module controller name, separated by dashes. cart-items-resource would point to CartItemsResourceController
     *
     * @return string
     */
    public function getController(): string;

    /**
     * @api
     *
     * This method should return FQCN to transfer object. This object it will be automatically populated from POST/PATCH
     * requests, and passed to REST controller actions as first argument. It is also used when creating JSONAPI resource objects.
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string;
}
