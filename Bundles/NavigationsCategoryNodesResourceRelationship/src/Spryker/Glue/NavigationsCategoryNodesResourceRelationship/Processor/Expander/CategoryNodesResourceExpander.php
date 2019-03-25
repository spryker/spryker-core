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

            $categoryNodeIds = $this->getAllCategoryNodeIds(
                $resourceAttributes->offsetGet(static::KEY_NODES)->getArrayCopy()
            );

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
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer[] $restNavigationNodes
     *
     * @return array
     */
    protected function getAllCategoryNodeIds(
        array $restNavigationNodes
    ): array {
        $categoryNodeIds = [];
        foreach ($restNavigationNodes as $restNavigationNode) {
            if ($this->isCategoryNavigationNode($restNavigationNode)) {
                $categoryNodeIds[] = $restNavigationNode->getResourceId();
                $categoryNodeIds = array_merge($this->getCategoryNodeChildrenIds($restNavigationNode), $categoryNodeIds);
            }
        }

        return $categoryNodeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNode
     *
     * @return array
     */
    protected function getCategoryNodeChildrenIds(
        RestNavigationNodeTransfer $restNavigationNode
    ): array {
        $categoryNodeIds = [];
        $navigationNodeChildren = $restNavigationNode->getChildren();
        foreach ($navigationNodeChildren as $navigationNodeChild) {
            $categoryNodeIds[] = $navigationNodeChild->getResourceId();
            $categoryNodeIds = array_merge($this->getCategoryNodeChildrenIds($navigationNodeChild), $categoryNodeIds);
        }

        return $categoryNodeIds;
    }
}
