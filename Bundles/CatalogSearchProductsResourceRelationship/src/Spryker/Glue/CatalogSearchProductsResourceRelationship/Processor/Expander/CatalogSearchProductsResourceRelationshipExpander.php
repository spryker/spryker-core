<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchProductsResourceRelationship\Processor\Expander;

use Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CatalogSearchProductsResourceRelationshipExpander implements CatalogSearchProductsResourceRelationshipExpanderInterface
{
    /**
     * @deprecated Will be removed in next major release.
     */
    protected const KEY_PRODUCTS = 'products';
    protected const KEY_ABSTRACT_PRODUCTS = 'abstractProducts';

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
            $attributes = $resource->getAttributes();
            if ($attributes && $attributes->offsetGet(static::KEY_ABSTRACT_PRODUCTS)) {
                $products = $attributes->offsetGet(static::KEY_ABSTRACT_PRODUCTS)->getArrayCopy();
                $this->addAbstractProductsToResource($products, $resource, $restRequest);

                return;
            }
            if ($attributes && $attributes->offsetGet(static::KEY_PRODUCTS)) {
                $products = $attributes->offsetGet(static::KEY_PRODUCTS)->getArrayCopy();
                $this->addAbstractProductsToResource($products, $resource, $restRequest);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer[] $products
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    protected function addAbstractProductsToResource(array $products, RestResourceInterface $resource, RestRequestInterface $restRequest): void
    {
        foreach ($products as $product) {
            if ($product->getAbstractSku()) {
                $abstractProduct = $this->productsResource->findProductAbstractBySku($product->getAbstractSku(), $restRequest);
                if ($abstractProduct) {
                    $resource->addRelationship($abstractProduct);
                }
            }
        }
    }
}
