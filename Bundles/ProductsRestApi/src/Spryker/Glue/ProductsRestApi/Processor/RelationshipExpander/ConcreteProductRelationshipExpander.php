<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\RelationshipExpander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;

class ConcreteProductRelationshipExpander implements ConcreteProductRelationshipExpanderInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    private $concreteProductsReader;

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
     * @return void
     */
    public function addResourceRelationshipsBySku(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $attributes = $resource->getAttributes();
            if ($attributes !== null && $attributes->offsetExists(static::KEY_SKU)) {
                $productResource = $this->concreteProductsReader->findOneByProductConcrete($attributes[static::KEY_SKU], $restRequest);
                if ($productResource !== null) {
                    $resource->addRelationship($productResource);
                }
            }
        }
    }
}
