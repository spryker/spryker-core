<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressReaderInterface;

class MerchantAddressByMerchantReferenceResourceRelationshipExpander implements MerchantAddressByMerchantReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressReaderInterface
     */
    protected $merchantAddressReader;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressReaderInterface $merchantAddressReader
     */
    public function __construct(MerchantAddressReaderInterface $merchantAddressReader)
    {
        $this->merchantAddressReader = $merchantAddressReader;
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
        $merchantRestResources = $this->merchantAddressReader->getMerchantAddressResources($merchantReferences);

        foreach ($resources as $resource) {
            $merchantReference = $resource->getId();
            if (!$merchantReference || !isset($merchantRestResources[$merchantReference])) {
                continue;
            }

            $resource->addRelationship($merchantRestResources[$merchantReference]);
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
