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

class CategoryNodesResourceExpander implements CategoryNodesResourceExpanderInterface
{
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
            if (!($resourceAttributes instanceof RestNavigationAttributesTransfer)) {
                continue;
            }

            $categoryNodeIds = $this->getCategoryNodeIds($resourceAttributes->getNodes()->getArrayCopy());

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
            if ($navigationNode->getNodeType() === static::NODE_TYPE_VALUE_CATEGORY) {
                $categoryNodeIds[] = $navigationNode->getResourceId();
            }
            $categoryNodeIds = array_merge(
                $this->getCategoryNodeIds($navigationNode->getChildren()->getArrayCopy()),
                $categoryNodeIds
            );
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
