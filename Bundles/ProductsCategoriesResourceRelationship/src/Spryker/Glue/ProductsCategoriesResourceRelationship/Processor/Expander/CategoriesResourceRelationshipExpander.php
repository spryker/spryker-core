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
        $locale = $restRequest->getMetadata()->getLocale();
        foreach ($resources as $resource) {
            $productCategoryNodeIds = $this->abstractProductsCategoriesReader
                ->findProductCategoryNodeIds($resource->getId(), $locale);

            foreach ($productCategoryNodeIds as $categoriesNodeId) {
                $resource->addRelationship($this->categoriesRestApiResource->findCategoryNodeById($categoriesNodeId, $locale));
            }
        }
    }
}
