<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\PermissionAwareTrait;
use Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;

class ConcreteProductPricesRelationshipExpander implements ConcreteProductPricesRelationshipExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface
     */
    protected $concreteProductPricesReader;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig
     */
    protected $productPricesRestApiConfig;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface $concreteProductPricesReader
     * @param \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig $productPricesRestApiConfig
     */
    public function __construct(
        ConcreteProductPricesReaderInterface $concreteProductPricesReader,
        ProductPricesRestApiConfig $productPricesRestApiConfig
    ) {
        $this->concreteProductPricesReader = $concreteProductPricesReader;
        $this->productPricesRestApiConfig = $productPricesRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
        if ($this->productPricesRestApiConfig->getPermissionCheckEnabled() && !$this->can('SeePricePermissionPlugin')) {
            return $resources;
        }

        foreach ($resources as $resource) {
            $concreteProductPricesResource = $this->concreteProductPricesReader
                ->findConcreteProductPricesBySku($resource->getId(), $restRequest);
            if ($concreteProductPricesResource) {
                $resource->addRelationship($concreteProductPricesResource);
            }
        }

        return $resources;
    }
}
