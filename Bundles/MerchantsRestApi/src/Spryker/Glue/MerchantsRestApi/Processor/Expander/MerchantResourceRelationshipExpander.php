<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantStorageReaderInterface;

class MerchantResourceRelationshipExpander implements MerchantResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantStorageReaderInterface
     */
    protected $merchantReader;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantStorageReaderInterface $merchantReader
     */
    public function __construct(MerchantStorageReaderInterface $merchantReader)
    {
        $this->merchantReader = $merchantReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addRelationshipsByOrderItemsMerchantReferences(array $resources, RestRequestInterface $restRequest): void
    {
        $merchantReferences = $this->getMerchantReferencesFromOrderResources($resources);

        if (!$merchantReferences) {
            return;
        }

        $merchantsRestResourceCollection = $this->merchantReader->getMerchantsResourceCollection($merchantReferences);

        foreach ($resources as $orderResource) {
            foreach ($orderResource->getAttributes()->getMerchantReferences() as $merchantReference) {
                $orderResource->addRelationship($merchantsRestResourceCollection[$merchantReference]);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getMerchantReferencesFromOrderResources(array $resources): array
    {
        $merchantReferences = [];

        foreach ($resources as $orderResource) {
            $merchantReferences = array_merge($merchantReferences, $orderResource->getAttributes()->getMerchantReferences());
        }

        return array_unique($merchantReferences);
    }
}
