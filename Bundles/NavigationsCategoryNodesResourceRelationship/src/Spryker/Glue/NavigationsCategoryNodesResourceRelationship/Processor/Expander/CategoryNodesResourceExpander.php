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

            $categoryNodeIds = $this->getCategoryNodeIds((array)$resourceAttributes->offsetGet(static::KEY_NODES));

            $this->addResourceRelationship($resource, $restRequest, array_unique($categoryNodeIds));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer[] $navigationNodes
     *
     * @return array
     */
    protected function getCategoryNodeIds(
        array $navigationNodes
    ): array {
        $categoryNodeIds = [];
        foreach ($navigationNodes as $navigationNode) {
            if (!$navigationNode->offsetExists(static::KEY_RESOURCE_ID)) {
                continue;
            }

            if ($this->isCategoryNavigationNode($navigationNode)) {
                $categoryNodeIds[] = $navigationNode->getResourceId();
            }
            $categoryNodeIds = array_merge(
                $this->getCategoryNodeIds((array)$navigationNode->getChildren()),
                $categoryNodeIds
            );
        }

        return $categoryNodeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer $restNavigationNodeTransfer
     *
     * @return bool
     */
    protected function isCategoryNavigationNode(RestNavigationNodeTransfer $restNavigationNodeTransfer): bool
    {
        if ($restNavigationNodeTransfer->offsetExists(static::KEY_NODE_TYPE)
            && $restNavigationNodeTransfer->offsetGet(static::KEY_NODE_TYPE) === static::NODE_TYPE_VALUE_CATEGORY
        ) {
            return true;
        }

        return false;
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
        $locale = $restRequest->getMetadata()->getLocale();

        foreach ($categoryNodeIds as $categoryNodeId) {
            $categoryNodeResource = $this->categoriesResource->findCategoryNodeById(
                $categoryNodeId,
                $locale
            );
            if ($categoryNodeResource) {
                $resource->addRelationship($categoryNodeResource);
            }
        }
    }
}
