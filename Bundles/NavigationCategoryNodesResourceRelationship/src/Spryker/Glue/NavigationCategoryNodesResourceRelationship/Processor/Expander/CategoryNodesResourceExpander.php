<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationCategoryNodesResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\RestNavigationNodeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface;

class CategoryNodesResourceExpander implements CategoryNodesResourceExpanderInterface
{
    protected const KEY_RESOURCE_ID = 'resourceId';
    protected const KEY_NODE_TYPE = 'nodeType';
    protected const KEY_NODES = 'nodes';

    protected const NODE_TYPE_VALUE_CATEGORY = 'category';

    /**
     * @var \Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
     */
    protected $categoriesResource;

    /**
     * @param \Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface $categoriesResource
     */
    public function __construct(NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface $categoriesResource)
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
            if (empty($resource->getAttributes()->offsetGet(static::KEY_NODES))) {
                continue;
            }

            foreach ($resource->getAttributes()->offsetGet(static::KEY_NODES) as $restNavigationNodeTransfer) {
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
        if (!empty($restNavigationNodeTransfer->offsetGet(static::KEY_RESOURCE_ID))
            && !empty($restNavigationNodeTransfer->offsetGet(static::KEY_NODE_TYPE))
            && $restNavigationNodeTransfer->offsetGet(static::KEY_NODE_TYPE) === static::NODE_TYPE_VALUE_CATEGORY
        ) {
            $categoryNode = $this->categoriesResource->findCategoryNodeById(
                $restNavigationNodeTransfer->offsetGet(static::KEY_RESOURCE_ID),
                $restRequest->getMetadata()->getLocale()
            );
            if ($categoryNode) {
                $resource->addRelationship($categoryNode);
            }
        }
    }
}
