<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class AbstractProductsRelationshipExpander implements AbstractProductsRelationshipExpanderInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface
     */
    protected $abstractProductsReader;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface $abstractProductsReader
     */
    public function __construct(AbstractProductsReaderInterface $abstractProductsReader)
    {
        $this->abstractProductsReader = $abstractProductsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsBySkus(array $resources, RestRequestInterface $restRequest): array
    {
        $productAbstractSkus = $this->findSkusByResources($resources);
        if (!$productAbstractSkus) {
            return [];
        }

        $abstractProductsResources = $this->abstractProductsReader->getProductAbstractsBySkus($productAbstractSkus, $restRequest);
        if (!$abstractProductsResources) {
            return [];
        }

        foreach ($resources as $resource) {
            $productAbstractSku = $this->findSku($resource->getAttributes());
            if (isset($abstractProductsResources[$productAbstractSku])) {
                $resource->addRelationship($abstractProductsResources[$productAbstractSku]);
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return array
     */
    protected function findSkusByResources(array $resources): array
    {
        $productAbstractSkus = [];
        foreach ($resources as $resource) {
            $productAbstractSku = $this->findSku($resource->getAttributes());
            if (!$productAbstractSku) {
                continue;
            }

            $productAbstractSkus[] = $productAbstractSku;
        }

        return $productAbstractSkus;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return string|null
     */
    protected function findSku(?AbstractTransfer $attributes): ?string
    {
        if ($attributes && $attributes instanceof RestPromotionalItemsAttributesTransfer) {
            return $attributes->offsetGet(static::KEY_SKU);
        }

        return null;
    }
}
