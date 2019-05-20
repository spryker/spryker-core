<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;

class SharedCartsResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, ResourceWithParentPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Configures available actions for `shared-carts` resource.
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
            ->addPost('post', true)
            ->addPatch('patch', true)
            ->addDelete('delete', true);
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
        return SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS;
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
        return 'shared-carts-resource';
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
        return RestSharedCartsAttributesTransfer::class;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getParentResourceType(): string
    {
        return SharedCartsRestApiConfig::RESOURCE_CARTS;
    }
}
