<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsProductsResourceRelationship\Processor\Expander;

use Spryker\Glue\ContentProductsProductsResourceRelationship\Dependency\RestResource\ContentProductsProductsResourceRelationshipToProductsResApiInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentProductsProductsResourceRelationshipExpander implements ContentProductsProductsResourceRelationshipExpanderInterface
{
    protected const KEY_ABSTRACT_PRODUCTS = 'abstractProducts';

    /**
     * @var \Spryker\Glue\ContentProductsProductsResourceRelationship\Dependency\RestResource\ContentProductsProductsResourceRelationshipToProductsResApiInterface
     */
    protected $productsResource;

    /**
     * @param \Spryker\Glue\ContentProductsProductsResourceRelationship\Dependency\RestResource\ContentProductsProductsResourceRelationshipToProductsResApiInterface $productsResource
     */
    public function __construct(ContentProductsProductsResourceRelationshipToProductsResApiInterface $productsResource)
    {
        $this->productsResource = $productsResource;
    }

    /**
     * @param array $resources
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
        /** @var \Generated\Shared\Transfer\RestContentAbstractProductTransfer $product */
        foreach ($products as $product) {
            if ($product->getSku()) {
                $abstractProduct = $this->productsResource->findProductAbstractBySku($product->getSku(), $restRequest);
                if ($abstractProduct) {
                    $resource->addRelationship($abstractProduct);
                }
            }
        }
    }
}
