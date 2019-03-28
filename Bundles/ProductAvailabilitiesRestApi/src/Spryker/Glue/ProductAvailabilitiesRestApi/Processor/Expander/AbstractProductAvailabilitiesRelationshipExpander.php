<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability\AbstractProductAvailabilitiesReaderInterface;

class AbstractProductAvailabilitiesRelationshipExpander implements AbstractProductAvailabilitiesRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability\AbstractProductAvailabilitiesReaderInterface
     */
    protected $abstractProductAvailabilitiesReader;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability\AbstractProductAvailabilitiesReaderInterface $abstractProductAvailabilitiesReader
     */
    public function __construct(AbstractProductAvailabilitiesReaderInterface $abstractProductAvailabilitiesReader)
    {
        $this->abstractProductAvailabilitiesReader = $abstractProductAvailabilitiesReader;
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
            $abstractProductAvailabilitiesResource = $this->abstractProductAvailabilitiesReader
                ->findAbstractProductAvailabilityBySku($resource->getId(), $restRequest);
            if ($abstractProductAvailabilitiesResource) {
                $resource->addRelationship($abstractProductAvailabilitiesResource);
            }
        }

        return $resources;
    }
}
