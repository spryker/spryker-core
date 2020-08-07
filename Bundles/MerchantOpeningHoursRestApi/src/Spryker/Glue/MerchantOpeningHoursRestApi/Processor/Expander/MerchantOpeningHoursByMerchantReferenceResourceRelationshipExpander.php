<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader\MerchantOpeningHoursReaderInterface;

class MerchantOpeningHoursByMerchantReferenceResourceRelationshipExpander implements MerchantOpeningHoursByMerchantReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader\MerchantOpeningHoursReaderInterface
     */
    protected $merchantOpeningHoursReader;

    /**
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader\MerchantOpeningHoursReaderInterface $merchantOpeningHoursReader
     */
    public function __construct(MerchantOpeningHoursReaderInterface $merchantOpeningHoursReader)
    {
        $this->merchantOpeningHoursReader = $merchantOpeningHoursReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $merchantReferences = $this->getMerchantReferences($resources);

        $merchantOpeningHoursResources = $this->merchantOpeningHoursReader
            ->getMerchantOpeningHoursResources($merchantReferences, $restRequest);

        foreach ($resources as $resource) {
            $merchantReference = $resource->getId();
            if (!isset($merchantOpeningHoursResources[$merchantReference])) {
                continue;
            }

            $resource->addRelationship($merchantOpeningHoursResources[$merchantReference]);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getMerchantReferences(array $resources): array
    {
        $references = [];
        foreach ($resources as $resource) {
            $resourceId = $resource->getId();
            if (!$resourceId) {
                continue;
            }

            $references[] = $resourceId;
        }

        return $references;
    }
}
