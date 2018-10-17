<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;

class ConcreteProductRelationshipExpanderByResourceId implements ConcreteProductRelationshipExpanderByResourceIdInterface
{
    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    protected $concreteProductReader;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface $concreteProductReader
     */
    public function __construct(ConcreteProductsReaderInterface $concreteProductReader)
    {
        $this->concreteProductReader = $concreteProductReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $concreteProductResource = $this->concreteProductReader->findOneByProductConcrete($resource->getId(), $restRequest);
            if ($concreteProductResource !== null) {
                $resource->addRelationship($concreteProductResource);
            }
        }
    }
}
