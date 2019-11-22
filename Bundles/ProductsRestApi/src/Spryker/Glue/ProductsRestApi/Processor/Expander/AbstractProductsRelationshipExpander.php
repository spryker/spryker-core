<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface;

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
     * @return void
     */
    public function addResourceRelationshipsBySkus(array $resources, RestRequestInterface $restRequest): void
    {
        $productAbstractSkus = $this->findSkusByResources($resources);
        if (!$productAbstractSkus) {
            return;
        }

        $abstractProductsResources = $this->abstractProductsReader->getProductAbstractsBySkus($productAbstractSkus, $restRequest);
        if (!$abstractProductsResources) {
            return;
        }

        foreach ($resources as $resource) {
            $productAbstractSku = $this->findProductAbstractSkuInRestResourceAttributes($resource);
            if (!isset($abstractProductsResources[$productAbstractSku])) {
                continue;
            }

            $resource->addRelationship($abstractProductsResources[$productAbstractSku]);
        }
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
            $productAbstractSku = $this->findProductAbstractSkuInRestResourceAttributes($resource);
            if (!$productAbstractSku) {
                continue;
            }

            $productAbstractSkus[] = $productAbstractSku;
        }

        return $productAbstractSkus;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return string|null
     */
    protected function findProductAbstractSkuInRestResourceAttributes(RestResourceInterface $resource): ?string
    {
        $attributes = $resource->getAttributes();
        if ($attributes && $attributes instanceof RestPromotionalItemsAttributesTransfer) {
            return $attributes->offsetGet(static::KEY_SKU);
        }

        return null;
    }
}
