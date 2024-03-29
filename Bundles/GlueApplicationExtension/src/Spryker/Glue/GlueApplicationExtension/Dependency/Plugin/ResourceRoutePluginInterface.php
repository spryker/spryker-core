<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Spryker\Glue\Kernel\ModuleNameAwareInterface;

/**
 * @deprecated Will be removed without replacement.
 */
interface ResourceRoutePluginInterface extends ModuleNameAwareInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Configuration for resource routing, how http methods map to controller actions, is action is protected, also possible
     * to add additional contextual data for action for later access when processing controller action.
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface;

    /**
     * @api
     *
     * Specification:
     *  - Resource name this plugins handles, must be plural string. This name also is matched with request path where resource
     * is provided.
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * @api
     *
     * Specification:
     *  - Module controller name, separated by dashes. cart-items-resource would point to CartItemsResourceController
     *
     * @return string
     */
    public function getController(): string;

    /**
     * @api
     *
     * Specification:
     *  - This method should return FQCN to transfer object. This object will be automatically populated from POST/PATCH
     * requests, and passed to REST controller actions as first argument. It is also used when creating JSONAPI resource objects.
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string;
}
