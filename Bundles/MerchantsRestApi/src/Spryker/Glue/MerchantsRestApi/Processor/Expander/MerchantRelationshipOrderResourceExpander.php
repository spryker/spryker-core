<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface;

class MerchantRelationshipOrderResourceExpander implements MerchantRelationshipOrderResourceExpanderInterface
{
    protected const RESOURCE_ATTRIBUTE_MERCHANT_REFERENCES = 'merchantReferences';

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

        foreach ($resources as $orderResource) {
            $orderResource = $this->addMerchantRelationships(
                $orderResource,
                $merchantsResources
            );
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
            $orderAttributes = $orderResource->getAttributes();

            if ($orderAttributes && $orderAttributes->offsetExists(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCES)) {
                $merchantReferences = array_merge(
                    $merchantReferences,
                    $orderAttributes->offsetGet(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCES)
                );
            }
        }

        return array_unique($merchantReferences);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $orderResource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $merchantsResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function addMerchantRelationships(
        RestResourceInterface $orderResource,
        array $merchantsResources
    ): RestResourceInterface {
        $orderAttributes = $orderResource->getAttributes();

        if ($orderAttributes && $orderAttributes->offsetExists(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCES)) {
            /** @var string[] $merchantReferences */
            $merchantReferences = $orderAttributes->offsetGet(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCES);
            $merchantsResources = $this->indexMerchantResourcesById($merchantsResources);

            foreach ($merchantReferences as $merchantReference) {
                if (!isset($merchantsResources[$merchantReference])) {
                    continue;
                }

                $orderResource->addRelationship($merchantsResources[$merchantReference]);
            }
        }

        return $orderResource;
    }
}
