<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Plugin;

use Generated\Shared\Transfer\RestCategoryNodesAttributesTransfer;
use Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class CategoryResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritDoc}
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection->addGet(
            CategoriesRestApiConfig::RESOURCE_CATEGORY_NODES_ACTION_NAME,
            CategoriesRestApiConfig::RESOURCE_CATEGORY_NODES_IS_PROTECTED
        );

        return $resourceRouteCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceType(): string
    {
        return CategoriesRestApiConfig::RESOURCE_CATEGORY_NODES;
    }

    /**
     * {@inheritDoc}
     */
    public function getController(): string
    {
        return CategoriesRestApiConfig::CONTROLLER_CATEGORY;
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceAttributesClassName(): string
    {
        return RestCategoryNodesAttributesTransfer::class;
    }
}
