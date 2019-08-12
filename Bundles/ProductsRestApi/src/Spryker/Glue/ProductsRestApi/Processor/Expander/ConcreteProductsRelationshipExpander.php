<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ConcreteProductsRelationshipExpander implements ConcreteProductsRelationshipExpanderInterface
{
    protected const KEY_SKU = 'sku';
    protected const KEY_PRODUCT_CONCRETE_IDS = 'product_concrete_ids';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    protected $concreteProductsReader;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface $concreteProductsReader
     */
    public function __construct(ConcreteProductsReaderInterface $concreteProductsReader)
    {
        $this->concreteProductsReader = $concreteProductsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsBySku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $skuProductConcrete = $this->findSku($resource->getAttributes());
            if (!$skuProductConcrete) {
                continue;
            }

            $concreteProductsResource = $this->concreteProductsReader->findProductConcreteBySku($skuProductConcrete, $restRequest);
            if ($concreteProductsResource) {
                $resource->addRelationship($concreteProductsResource);
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByProductConcreteIds(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $productConcreteSkus = $this->findProductConcreteSkus($resource->getAttributes());
            if (!$productConcreteSkus) {
                continue;
            }
            foreach ($productConcreteSkus as $skuProductConcrete) {
                $concreteProductsResource = $this->concreteProductsReader->findProductConcreteBySku($skuProductConcrete, $restRequest);
                if ($concreteProductsResource) {
                    $resource->addRelationship($concreteProductsResource);
                }
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return array|null
     */
    protected function findProductConcreteSkus(?AbstractTransfer $attributes): ?array
    {
        if ($attributes
            && $attributes instanceof AbstractProductsRestAttributesTransfer
            && !empty($attributes->getAttributeMap()[static::KEY_PRODUCT_CONCRETE_IDS])
        ) {
            return $attributes->getAttributeMap()[static::KEY_PRODUCT_CONCRETE_IDS];
        }

        return null;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return string|null
     */
    protected function findSku(?AbstractTransfer $attributes): ?string
    {
        if ($attributes
            && $attributes->offsetExists(static::KEY_SKU)
            && $attributes->offsetGet(static::KEY_SKU)
        ) {
            return $attributes->offsetGet(static::KEY_SKU);
        }

        return null;
    }
}
