<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface;

class ConcreteProductPricesRelationshipExpander implements ConcreteProductPricesRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface
     */
    protected $concreteProductPricesReader;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface $concreteProductPricesReader
     */
    public function __construct(ConcreteProductPricesReaderInterface $concreteProductPricesReader)
    {
        $this->concreteProductPricesReader = $concreteProductPricesReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
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
