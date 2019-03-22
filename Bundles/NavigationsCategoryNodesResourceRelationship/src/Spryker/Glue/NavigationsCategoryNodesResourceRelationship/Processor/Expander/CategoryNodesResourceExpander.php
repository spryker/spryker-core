<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\RestNavigationNodeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface;

class CategoryNodesResourceExpander implements CategoryNodesResourceExpanderInterface
{
    protected const KEY_RESOURCE_ID = 'resourceId';
    protected const KEY_NODE_TYPE = 'nodeType';
    protected const KEY_NODES = 'nodes';

    protected const NODE_TYPE_VALUE_CATEGORY = 'category';

    /**
     * @var \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
     */
    protected $categoriesResource;

    /**
     * @param \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface $categoriesResource
     */
    public function __construct(NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface $categoriesResource)
    {
        $this->categoriesResource = $categoriesResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $resourceAttributes = $resource->getAttributes();
            if (!$resourceAttributes->offsetExists(static::KEY_NODES)
                || !is_iterable($resourceAttributes->offsetGet(static::KEY_NODES))
            ) {
                continue;
            }

            $categoryNodeIds = [];
            foreach ($resourceAttributes->offsetGet(static::KEY_NODES) as $restNavigationNodeTransfer) {
                $categoryNodeIds = $this->getAllCategoryNodeIds($restNavigationNodeTransfer, $categoryNodeIds);
            }

            $this->addResourceRelationship($resource, $restRequest, $categoryNodeIds);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    protected function addResourceRelationship(
        RestResourceInterface $resource,
        RestRequestInterface $restRequest,
        array $categoryNodeIds
    ): void {
        if (!count($categoryNodeIds)) {
            return;
        }

        foreach ($categoryNodeIds as $categoryNodeId) {
            $categoryNode = $this->categoriesResource->findCategoryNodeById(
                $categoryNodeId,
                $restRequest->getMetadata()->getLocale()
            );
            if ($categoryNode) {
                $resource->addRelationship($categoryNode);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNodeTransfer
     *
     * @return bool
     */
    protected function isCategoryNavigationNode(RestNavigationNodeTransfer $restNavigationNodeTransfer): bool
    {
        if ($restNavigationNodeTransfer->offsetExists(static::KEY_RESOURCE_ID)
            && $restNavigationNodeTransfer->offsetExists(static::KEY_NODE_TYPE)
            && $restNavigationNodeTransfer->offsetGet(static::KEY_NODE_TYPE) === static::NODE_TYPE_VALUE_CATEGORY
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNodeTransfer
     * @param int[] $categoryNodeIds
     *
     * @return array
     */
    protected function getAllCategoryNodeIds(
        RestNavigationNodeTransfer $restNavigationNodeTransfer,
        array $categoryNodeIds
    ): array {
        if ($this->isCategoryNavigationNode($restNavigationNodeTransfer)) {
            $categoryNodeIds[] = $restNavigationNodeTransfer->offsetGet(static::KEY_RESOURCE_ID);
            $categoryNodeIds = $this->getCategoryNodeChildrenIds($restNavigationNodeTransfer, $categoryNodeIds);
        }

        return $categoryNodeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNodeTransfer
     * @param int[] $categoryNodeIds
     *
     * @return array
     */
    protected function getCategoryNodeChildrenIds(
        RestNavigationNodeTransfer $restNavigationNodeTransfer,
        array $categoryNodeIds
    ): array {
        $navigationNodeChildren = $restNavigationNodeTransfer->getChildren();
        if (!$navigationNodeChildren->count()) {
            return $categoryNodeIds;
        }

        foreach ($navigationNodeChildren as $navigationNodeChild) {
            $categoryNodeIds[] = $navigationNodeChild->getResourceId();
            $categoryNodeIds = $this->getCategoryNodeChildrenIds($navigationNodeChild, $categoryNodeIds);
        }

        return $categoryNodeIds;
    }
}
