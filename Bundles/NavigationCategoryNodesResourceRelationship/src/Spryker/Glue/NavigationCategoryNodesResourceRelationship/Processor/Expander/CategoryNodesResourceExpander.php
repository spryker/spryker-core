<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationCategoryNodesResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\RestNavigationNodeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface;

class CategoryNodesResourceExpander implements CategoryNodesResourceExpanderInterface
{
    protected const RESOURCE_ID = 'resourceId';
    protected const NODE_TYPE = 'nodeType';
    protected const NODE_TYPE_CATEGORY_VALUE = 'category';
    protected const NODES = 'nodes';

    /**
     * @var \Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface
     */
    protected $categoriesResource;

    /**
     * @param \Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface $categoriesResource
     */
    public function __construct(NavigationCategoryNodesResourceRelationshipToCategoriesRestApiInterface $categoriesResource)
    {
        $this->categoriesResource = $categoriesResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function expandResourceWithCategoryNode(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            foreach ($resource->getAttributes()[static::NODES] as $restNavigationNodeTransfer) {
                $this->addResourceRelationship($resource, $restRequest, $restNavigationNodeTransfer);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNodeTransfer
     *
     * @return void
     */
    protected function addResourceRelationship(
        RestResourceInterface $resource,
        RestRequestInterface $restRequest,
        RestNavigationNodeTransfer $restNavigationNodeTransfer
    ): void {
        if ($restNavigationNodeTransfer[static::RESOURCE_ID]) {
            $categoryNode = $this->categoriesResource->findCategoryNodeById(
                $restNavigationNodeTransfer[static::RESOURCE_ID],
                $restRequest->getMetadata()->getLocale()
            );

            if ($categoryNode !== null && $restNavigationNodeTransfer[static::NODE_TYPE] === static::NODE_TYPE_CATEGORY_VALUE) {
                $resource->addRelationship($categoryNode);
            }
        }
    }
}
