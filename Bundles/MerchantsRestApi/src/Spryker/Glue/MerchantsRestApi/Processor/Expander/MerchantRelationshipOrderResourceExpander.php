<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestOrdersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface;

class MerchantRelationshipOrderResourceExpander implements MerchantRelationshipOrderResourceExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface
     */
    protected $merchantReader;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface $merchantReader
     */
    public function __construct(MerchantReaderInterface $merchantReader)
    {
        $this->merchantReader = $merchantReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addRelationshipsByOrderMerchantReferences(array $resources, RestRequestInterface $restRequest): void
    {
        $merchantReferences = $this->getMerchantReferencesFromOrderResources($resources);

        if (!$merchantReferences) {
            return;
        }

        $merchantsResources = $this->merchantReader->getMerchantsResources($merchantReferences);
        $merchantsResources = $this->indexMerchantResourcesById($merchantsResources);

        foreach ($resources as $orderResource) {
            $merchantReferences = $orderResource->getAttributes()->offsetGet(RestOrdersAttributesTransfer::MERCHANT_REFERENCES);
            foreach ($merchantReferences as $merchantReference) {
                if (!isset($merchantsResources[$merchantReference])) {
                    continue;
                }

                $orderResource->addRelationship($merchantsResources[$merchantReference]);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $merchantRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function indexMerchantResourcesById(array $merchantRestResources): array
    {
        $indexedMerchantRestResources = [];

        foreach ($merchantRestResources as $merchantRestResource) {
            $indexedMerchantRestResources[$merchantRestResource->getId()] = $merchantRestResource;
        }

        return $indexedMerchantRestResources;
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
            $merchantReferences = array_merge(
                $merchantReferences,
                $orderResource->getAttributes()->offsetGet(RestOrdersAttributesTransfer::MERCHANT_REFERENCES)
            );
        }

        return array_unique($merchantReferences);
    }
}
