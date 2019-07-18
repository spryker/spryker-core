<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\RestNavigationAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface;

class CategoryNodeResourceExpander implements CategoryNodeResourceExpanderInterface
{
    protected const NODE_TYPE_VALUE_CATEGORY = 'category';

    /**
     * @var \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface
     */
    protected $categoriesRestApiResource;

    /**
     * @param \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface $categoriesRestApiResource
     */
    public function __construct(NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceInterface $categoriesRestApiResource)
    {
        $this->categoriesRestApiResource = $categoriesRestApiResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $resourceAttributes = $resource->getAttributes();
            if (!($resourceAttributes instanceof RestNavigationAttributesTransfer)) {
                continue;
            }

            $categoryNodeIds = $this->getCategoryNodeIds($resourceAttributes->getNodes()->getArrayCopy());

            $this->addResourceRelationship($resource, $restRequest, array_unique($categoryNodeIds));
        }

        return $resources;
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationNodeTransfer[] $navigationNodes
     *
     * @return array
     */
    protected function getCategoryNodeIds(array $navigationNodes): array
    {
        $categoryNodeIds = [];
        foreach ($navigationNodes as $navigationNode) {
            if ($navigationNode->getNodeType() === static::NODE_TYPE_VALUE_CATEGORY) {
                $categoryNodeIds[] = $navigationNode->getResourceId();
            }
            $childCategoryNodes = $this->getCategoryNodeIds($navigationNode->getChildren()->getArrayCopy());
            $categoryNodeIds = array_merge($childCategoryNodes, $categoryNodeIds);
        }

        return $categoryNodeIds;
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
            $categoryNodeResource = $this->categoriesRestApiResource
                ->findCategoryNodeById($categoryNodeId, $locale);
            if ($categoryNodeResource) {
                $resource->addRelationship($categoryNodeResource);
            }
        }
    }
}
