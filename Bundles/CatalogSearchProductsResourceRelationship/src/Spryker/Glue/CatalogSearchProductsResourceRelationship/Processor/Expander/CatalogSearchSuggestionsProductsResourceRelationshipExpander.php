<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchProductsResourceRelationship\Processor\Expander;

use Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CatalogSearchSuggestionsProductsResourceRelationshipExpander implements CatalogSearchSuggestionsProductsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * CatalogSearchProductsResourceRelationshipExpander constructor.
     *
     * @param \Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface $productsResource
     */
    public function __construct(CatalogSearchProductsResourceRelationshipToProductsRestApiInterface $productsResource)
    {
        $this->productsResource = $productsResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            /** @var \Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer $attributes */
            $attributes = $resource->getAttributes();
            if ($attributes && $attributes->getProducts()) {
                $this->addAbstractProductsToResource($attributes->getProducts(), $resource, $restRequest);
            }
        }
    }

    /**
     * @param array $products
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    protected function addAbstractProductsToResource(array $products, RestResourceInterface $resource, RestRequestInterface $restRequest): void
    {
        foreach ($products as $product) {
            if (isset($product['abstract_sku'])) {
                $abstractProduct = $this->productsResource->findOneByProductAbstractSku($product['abstract_sku'], $restRequest);
                if ($abstractProduct) {
                    $resource->addRelationship($abstractProduct);
                }
            }
        }
    }
}
