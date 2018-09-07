<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader\AbstractProductsCategoriesReaderInterface;

class AbstractProductsCategoriesResourceRelationshipExpander implements AbstractProductsCategoriesResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface
     */
    protected $categoriesResource;

    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader\AbstractProductsCategoriesReaderInterface
     */
    protected $abstractProductsCategoriesReader;

    /**
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface $categoriesResource
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader\AbstractProductsCategoriesReaderInterface $abstractProductsCategoriesReader
     */
    public function __construct(
        ProductsCategoriesResourceRelationToCategoriesRestApiInterface $categoriesResource,
        AbstractProductsCategoriesReaderInterface $abstractProductsCategoriesReader
    ) {
        $this->categoriesResource = $categoriesResource;
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
            $sku = $resource->getId();
            $productCategoryNodeIds = $this->abstractProductsCategoriesReader
                ->findProductAbstractCategoryBySku($sku, $locale);

            foreach ($productCategoryNodeIds as $categoriesNodeId) {
                $resource->addRelationship($this->categoriesResource->findCategoryNodeById($categoriesNodeId, $locale));
            }
        }
    }
}
