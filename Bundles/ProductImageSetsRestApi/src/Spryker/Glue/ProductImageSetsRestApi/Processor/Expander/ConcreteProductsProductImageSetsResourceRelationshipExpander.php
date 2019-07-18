<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\ConcreteProductImageSetsReaderInterface;

class ConcreteProductsProductImageSetsResourceRelationshipExpander implements ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\ConcreteProductImageSetsReaderInterface
     */
    protected $concreteProductImageSetsReader;

    /**
     * @param \Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\ConcreteProductImageSetsReaderInterface $concreteProductImageSetsReader
     */
    public function __construct(ConcreteProductImageSetsReaderInterface $concreteProductImageSetsReader)
    {
        $this->concreteProductImageSetsReader = $concreteProductImageSetsReader;
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
            $concreteProductImageSetsResource = $this->concreteProductImageSetsReader
                ->findConcreteProductImageSetsBySku($resource->getId(), $restRequest);
            if ($concreteProductImageSetsResource !== null) {
                $resource->addRelationship($concreteProductImageSetsResource);
            }
        }
    }
}
