<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface;

class MerchantAddressesByMerchantReferenceResourceRelationshipExpander implements MerchantAddressesByMerchantReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface
     */
    protected $merchantAddressesRestResponseBuilder;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface $merchantAddressesRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient,
        MerchantAddressesRestResponseBuilderInterface $merchantAddressesRestResponseBuilder
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantAddressesRestResponseBuilder = $merchantAddressesRestResponseBuilder;
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
        $merchantStorageTransfers = $this->merchantStorageClient->findByMerchantReference($merchantReferences);

        $merchantStorageTransfers = $this->setMerchantReferenceKeyToMerchantStorageTransfers($merchantStorageTransfers);

        foreach ($resources as $resource) {
            $merchantStorageTransfer = $merchantStorageTransfers[$resource->getId()];
            if (empty($merchantStorageTransfer->getMerchantStorageProfile()->getAddressCollection())) {
                continue;
            }
            $restMerchantAddressesResource = $this->merchantAddressesRestResponseBuilder->createMerchantAddressesRestResource(
                $merchantStorageTransfer->getMerchantStorageProfile()->getAddressCollection()->getArrayCopy(),
                $resource->getId()
            );

            $resource->addRelationship($restMerchantAddressesResource);
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
            $references[] = $resource->getId();
        }

        return $references;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    protected function setMerchantReferenceKeyToMerchantStorageTransfers(array $merchantStorageTransfers): array
    {
        $merchantStorageTransfersWithMerchantReferenceKey = [];
        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantReference = $merchantStorageTransfer->getMerchantReference();
            $merchantStorageTransfersWithMerchantReferenceKey[$merchantReference] = $merchantStorageTransfer;
        }

        return $merchantStorageTransfersWithMerchantReferenceKey;
    }
}
