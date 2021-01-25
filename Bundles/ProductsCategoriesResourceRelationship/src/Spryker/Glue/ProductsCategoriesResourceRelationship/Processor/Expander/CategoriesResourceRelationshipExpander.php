<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiResourceInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader\AbstractProductsCategoriesReaderInterface;

class CategoriesResourceRelationshipExpander implements CategoriesResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiResourceInterface
     */
    protected $categoriesRestApiResource;

    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader\AbstractProductsCategoriesReaderInterface
     */
    protected $abstractProductsCategoriesReader;

    /**
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiResourceInterface $categoriesRestApiResource
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader\AbstractProductsCategoriesReaderInterface $abstractProductsCategoriesReader
     */
    public function __construct(
        ProductsCategoriesResourceRelationToCategoriesRestApiResourceInterface $categoriesRestApiResource,
        AbstractProductsCategoriesReaderInterface $abstractProductsCategoriesReader
    ) {
        $this->categoriesRestApiResource = $categoriesRestApiResource;
        $this->abstractProductsCategoriesReader = $abstractProductsCategoriesReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $localeName = $restRequest->getMetadata()->getLocale();

        $productAbstractSkus = [];
        foreach ($resources as $restResource) {
            $productAbstractSkus[] = $restResource->getId();
        }

        $productCategoryNodeIds = $this->abstractProductsCategoriesReader
            ->findProductCategoryNodeIdsBySkus($productAbstractSkus, $localeName);

        if (count($productCategoryNodeIds) === 0) {
            return;
        }

        $categoryNodeIds = array_unique(array_merge(...array_values($productCategoryNodeIds)));

        $categoryNodesRestResources = $this->categoriesRestApiResource
            ->findCategoryNodeByIds($categoryNodeIds, $localeName);

        foreach ($resources as $restResource) {
            if (!array_key_exists($restResource->getId(), $productCategoryNodeIds)) {
                continue;
            }

            foreach ($productCategoryNodeIds[$restResource->getId()] as $categoryNodeId) {
                if (!array_key_exists($categoryNodeId, $categoryNodesRestResources)) {
                    continue;
                }

                $restResource->addRelationship($categoryNodesRestResources[$categoryNodeId]);
            }
        }
    }
}
