<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchProductsResourceRelationship\Processor\Expander;

use ArrayObject;
use Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CatalogSearchSuggestionsProductsResourceRelationshipExpander implements CatalogSearchSuggestionsProductsResourceRelationshipExpanderInterface
{
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';
    protected const KEY_ABSTRACT_SKU_CAMEL_CASE = 'abstractSku';

    /**
     * @var \Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface
     */
    protected $productsResource;

    /**
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
            $products = $attributes->getProducts();

            if ($products instanceof ArrayObject) {
                $products = $products->getArrayCopy();
            }

            if ($products) {
                $this->addAbstractProductsToResource($products, $resource, $restRequest);
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
            $productAbstractSku = '';
            if (isset($product[static::KEY_ABSTRACT_SKU_CAMEL_CASE])) {
                $productAbstractSku = $product[static::KEY_ABSTRACT_SKU_CAMEL_CASE];
            }

            if (isset($product[static::KEY_ABSTRACT_SKU])) {
                $productAbstractSku = $product[static::KEY_ABSTRACT_SKU];
            }

            if (!$productAbstractSku) {
                continue;
            }

            $abstractProduct = $this->productsResource->findProductAbstractBySku($productAbstractSku, $restRequest);
            if ($abstractProduct) {
                $resource->addRelationship($abstractProduct);
            }
        }
    }
}
