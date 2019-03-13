<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\NavigationsCategoryNodesResourceRelationshipFactory getFactory()
 */
class CategoryNodeByIdResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @uses \Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig::RESOURCE_CATEGORY_NODES
     */
    protected const RESOURCE_CATEGORY_NODES = 'category-nodes';

    /**
     * {@inheritdoc}
     *  - Adds category node resource as relationship.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createCategoryNodesResourceExpander()
            ->expandResourceWithCategoryNode($resources, $restRequest);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RESOURCE_CATEGORY_NODES;
    }
}
