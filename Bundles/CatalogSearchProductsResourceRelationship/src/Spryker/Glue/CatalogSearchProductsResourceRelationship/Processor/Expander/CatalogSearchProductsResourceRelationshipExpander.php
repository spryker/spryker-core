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
        $allProductAbstractSkus = $this->getProductAbstractSkus($resources);

        $productAbstractRestResources = $this->productsResource
            ->getProductAbstractsBySkus(array_merge(...array_values($allProductAbstractSkus)), $restRequest);

        foreach ($resources as $restResource) {
            if (!array_key_exists($this->getRestResourceIdentifier($restResource), $allProductAbstractSkus)) {
                continue;
            }

            foreach ($allProductAbstractSkus[$this->getRestResourceIdentifier($restResource)] as $productAbstractSku) {
                if (!array_key_exists($productAbstractSku, $productAbstractRestResources)) {
                    continue;
                }

                $restResource->addRelationship($productAbstractRestResources[$productAbstractSku]);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return array
     */
    protected function getProductAbstractSkus(array $resources): array
    {
        $productAbstractSkus = [];

        foreach ($resources as $resource) {
            $attributes = $resource->getAttributes();

            if ($attributes && $attributes->offsetExists(static::KEY_ABSTRACT_PRODUCTS)) {
                $productAbstractSkus[$this->getRestResourceIdentifier($resource)] = $this->getProductAbstractSkusForRestResource(
                    $attributes->offsetGet(static::KEY_ABSTRACT_PRODUCTS)
                );

                continue;
            }

            if ($attributes && $attributes->offsetExists(static::KEY_PRODUCTS)) {
                $productAbstractSkus[$this->getRestResourceIdentifier($resource)] = $this->getProductAbstractSkusForRestResource(
                    $attributes->offsetGet(static::KEY_PRODUCTS)
                );
            }
        }

        return $productAbstractSkus;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string
     */
    protected function getRestResourceIdentifier(RestResourceInterface $restResource): string
    {
        return $restResource->getType() . ':' . $restResource->getId();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer[] $products
     *
     * @return string[]
     */
    protected function getProductAbstractSkusForRestResource(ArrayObject $products): array
    {
        $productAbstractSkus = [];

        foreach ($products as $product) {
            if ($product->getAbstractSku()) {
                $productAbstractSkus[] = $product->getAbstractSku();
            }
        }

        return $productAbstractSkus;
    }
}
