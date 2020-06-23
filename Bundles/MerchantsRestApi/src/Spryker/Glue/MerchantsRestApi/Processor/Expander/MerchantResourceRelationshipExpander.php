<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface;

class MerchantResourceRelationshipExpander implements MerchantResourceRelationshipExpanderInterface
{
    protected const RESOURCE_ATTRIBUTE_MERCHANT_REFERENCE = 'merchantReference';

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
    public function addRelationshipsByMerchantReference(array $resources, RestRequestInterface $restRequest): void
    {
        $merchantReferences = $this->getMerchantReferences($resources);

        if (!$merchantReferences) {
            return;
        }

        $merchantsResources = $this->merchantReader->getMerchantsResources(
            $merchantReferences,
            $restRequest->getMetadata()->getLocale()
        );

        $this->addMerchantRelationships($resources, $merchantsResources);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getMerchantReferences(array $resources): array
    {
        $merchantReferences = [];

        foreach ($resources as $restResource) {
            $attributesTransfer = $restResource->getAttributes();

            if ($attributesTransfer && $attributesTransfer->offsetExists(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCE)) {
                $merchantReferences[] = $attributesTransfer->offsetGet(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCE);
            }
        }

        return array_unique($merchantReferences);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $merchantsResources
     *
     * @return void
     */
    protected function addMerchantRelationships(array $resources, array $merchantsResources): void
    {
        foreach ($resources as $restResource) {
            $attributesTransfer = $restResource->getAttributes();

            if (
                !$attributesTransfer
                || !$attributesTransfer->offsetExists(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCE)
            ) {
                continue;
            }

            $merchantReference = $attributesTransfer->offsetGet(static::RESOURCE_ATTRIBUTE_MERCHANT_REFERENCE);

            if (!isset($merchantsResources[$merchantReference])) {
                continue;
            }

            $restResource->addRelationship($merchantsResources[$merchantReference]);
        }
    }
}
