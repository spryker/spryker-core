<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability\ConcreteProductAvailabilitiesReaderInterface;

class ConcreteProductAvailabilitiesRelationshipExpander implements ConcreteProductAvailabilitiesRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability\ConcreteProductAvailabilitiesReaderInterface
     */
    protected $concreteProductAvailabilitiesReader;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability\ConcreteProductAvailabilitiesReaderInterface $concreteProductAvailabilitiesReader
     */
    public function __construct(ConcreteProductAvailabilitiesReaderInterface $concreteProductAvailabilitiesReader)
    {
        $this->concreteProductAvailabilitiesReader = $concreteProductAvailabilitiesReader;
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
            $concreteProductAvailabilitiesResource = $this->concreteProductAvailabilitiesReader
                ->findConcreteProductAvailabilityBySku($resource->getId(), $restRequest);
            if ($concreteProductAvailabilitiesResource) {
                $resource->addRelationship($concreteProductAvailabilitiesResource);
            }
        }

        return $resources;
    }
}
